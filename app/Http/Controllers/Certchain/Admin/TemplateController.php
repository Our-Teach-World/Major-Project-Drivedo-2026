<?php

namespace App\Http\Controllers\Certchain\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertchainTemplate as CertificateTemplate;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index()
    {
        $templates = CertificateTemplate::with('creator')->latest()->paginate(10);
        return view('certchain.admin.templates.index', compact('templates'));
    }

    public function create()
    {
        $defaultHtml = $this->getDefaultTemplate();
        return view('certchain.admin.templates.create', compact('defaultHtml'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:participation,achievement,completion,winner',
            'html_content' => 'required|string',
            'border_style' => 'required|in:classic,modern,minimal',
            'is_active'    => 'boolean',
        ]);

        CertificateTemplate::create([
            ...$data,
            'is_active'  => $request->boolean('is_active', true),
            'created_by' => session('admin_id'),
        ]);

        return redirect()->route('admin.certchain.templates.index')->with('success', 'Template created successfully!');
    }

    public function edit(CertificateTemplate $template)
    {
        return view('certchain.admin.templates.edit', compact('template'));
    }

    public function update(Request $request, CertificateTemplate $template)
    {
        $data = $request->validate([
            'name'         => 'required|string|max:255',
            'type'         => 'required|in:participation,achievement,completion,winner',
            'html_content' => 'required|string',
            'border_style' => 'required|in:classic,modern,minimal',
        ]);

        $template->update([
            ...$data,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.certchain.templates.index')->with('success', 'Template updated!');
    }

    public function preview(Request $request, CertificateTemplate $template)
    {
        $sampleData = [
            'student_name'       => 'Rahul Sharma',
            'enrollment_number'  => '0801CS211001',
            'student_branch'     => 'Computer Science',
            'student_year'       => '3rd Year',
            'event_name'         => 'National Tech Symposium 2024',
            'event_date'         => '15 Nov 2024',
            'event_type'         => 'Symposium',
            'venue'              => 'Main Auditorium',
            'achievement'        => '1st Prize',
            'description'        => 'for outstanding performance in the Hackathon competition',
            'issued_date'        => date('d M Y'),
            'issued_by'          => 'Dr. Priya Singh',
            'issuer_designation' => 'HOD, Computer Science',
            'certificate_id'     => 'NATI-2024-AB1234',
            'block_hash'         => 'a3f8c1e2...',
            'college_name'       => config('app.college_name', 'Your College'),
            'qr_code'            => '',
        ];

        $html = $template->render($sampleData);
        return response($html);
    }

    public function destroy(CertificateTemplate $template)
    {
        if ($template->certificates()->count() > 0) {
            return back()->with('error', 'Cannot delete template that has issued certificates.');
        }
        $template->delete();
        return redirect()->route('admin.certchain.templates.index')->with('success', 'Template deleted.');
    }

    private function getDefaultTemplate(): string
    {
        return <<<'HTML'
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
  @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Lato:wght@300;400&display=swap');
  * { margin: 0; padding: 0; box-sizing: border-box; }
  body { width: 297mm; height: 210mm; font-family: 'Lato', sans-serif; background: #fff; }
  .certificate {
    width: 100%; height: 100%;
    border: 12px solid #1a3a5c;
    outline: 3px solid #c9a84c;
    outline-offset: -18px;
    padding: 30px 50px;
    display: flex; flex-direction: column;
    align-items: center; justify-content: center;
    text-align: center; position: relative;
  }
  .college-name { font-size: 13px; letter-spacing: 3px; color: #1a3a5c; text-transform: uppercase; margin-bottom: 6px; }
  .title { font-family: 'Cinzel', serif; font-size: 40px; color: #c9a84c; margin: 10px 0; }
  .subtitle { font-size: 13px; letter-spacing: 4px; color: #555; text-transform: uppercase; margin-bottom: 20px; }
  .presented-to { font-size: 13px; color: #777; margin-bottom: 5px; }
  .student-name { font-family: 'Cinzel', serif; font-size: 32px; color: #1a3a5c; border-bottom: 2px solid #c9a84c; padding-bottom: 6px; margin: 5px 0 15px; }
  .body-text { font-size: 13px; color: #444; line-height: 1.8; max-width: 550px; }
  .event-name { font-weight: bold; color: #1a3a5c; }
  .footer { display: flex; justify-content: space-between; width: 100%; margin-top: 30px; align-items: flex-end; }
  .sig-block { text-align: center; }
  .sig-line { border-top: 1px solid #333; padding-top: 5px; width: 160px; font-size: 11px; }
  .cert-id { font-size: 9px; color: #aaa; position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); white-space: nowrap; }
  .qr { position: absolute; bottom: 25px; right: 40px; width: 60px; height: 60px; }
</style>
</head>
<body>
<div class="certificate">
  <p class="college-name">{{college_name}}</p>
  <h1 class="title">Certificate</h1>
  <p class="subtitle">of {{achievement}}</p>
  <p class="presented-to">This is proudly presented to</p>
  <h2 class="student-name">{{student_name}}</h2>
  <p class="body-text">
    Enrollment No: <strong>{{enrollment_number}}</strong> &nbsp;|&nbsp; {{student_branch}} &nbsp;|&nbsp; {{student_year}}<br><br>
    {{description}} at the<br>
    <span class="event-name">{{event_name}}</span><br>
    held on {{event_date}} at {{venue}}
  </p>
  <div class="footer">
    <div class="sig-block">
      <div class="sig-line">{{issued_by}}<br><small>{{issuer_designation}}</small></div>
    </div>
    <div class="sig-block">
      <div class="sig-line">Date: {{issued_date}}</div>
    </div>
  </div>
  <p class="cert-id">Certificate ID: {{certificate_id}} &nbsp;|&nbsp; Block Hash: {{block_hash}}</p>
  <div class="qr">{{{qr_code}}}</div>
</div>
</body>
</html>
HTML;
    }
}
