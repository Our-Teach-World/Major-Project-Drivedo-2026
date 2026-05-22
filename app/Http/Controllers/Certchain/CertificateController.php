<?php

namespace App\Http\Controllers\Certchain;

use App\Http\Controllers\Controller;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Event;
use App\Services\BlockchainService;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    private function getStudentsData()
    {
        return \App\Models\Student::with('user')->get()->map(function($s) {
            $year = match((int) $s->semester) {
                1, 2 => '1st Year',
                3, 4 => '2nd Year',
                5, 6 => '3rd Year',
                7, 8 => '4th Year',
                default => '',
            };
            return [
                'name' => $s->user?->name ?? '',
                'email' => $s->user?->email ?? '',
                'enrollment_no' => $s->enrollment_no ?? '',
                'branch' => $s->branch ?? '',
                'year' => $year,
            ];
        })->filter(fn($s) => !empty($s['enrollment_no']))->values()->toJson();
    }

    public function __construct(
        protected CertificateService $certService,
        protected BlockchainService $blockchain,
    ) {
    }

    // ── List ──────────────────────────────────────────────
    public function index(Request $request)
    {
        $query = Certificate::with(['event', 'issuer', 'blockchainBlock']);

        // Non-admins only see their own issued certs
        if (!auth()->user()->hasRole('admin')) {
            $query->where('issued_by', auth()->id());
        }

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($qb) use ($q) {
                $qb->where('student_name', 'like', "%{$q}%")
                    ->orWhere('enrollment_number', 'like', "%{$q}%")
                    ->orWhere('certificate_id', 'like', "%{$q}%");
            });
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        $certificates = $query->latest()->paginate(15)->withQueryString();
        $events = Event::orderBy('name')->get();

        return view('certificates.index', compact('certificates', 'events'));
    }

    // ── Issue Single ──────────────────────────────────────
    public function create()
    {
        $events = Event::where('status', 'active')->orderBy('name')->get();
        $templates = CertificateTemplate::where('is_active', true)->get();
        $studentsJson = $this->getStudentsData();
        return view('certificates.create', compact('events', 'templates', 'studentsJson'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:events,id',
            'template_id' => 'required|exists:certificate_templates,id',
            'student_name' => 'required|string|max:255',
            'student_email' => 'required|email',
            'enrollment_number' => 'required|string|max:50',
            'student_branch' => 'nullable|string|max:100',
            'student_year' => 'nullable|string|max:20',
            'achievement' => 'required|string|max:100',
            'description' => 'nullable|string',
            'issued_date' => 'required|date',
            'send_email' => 'boolean',
        ]);

        // Check for duplicate: same student + same event
        $exists = Certificate::where('enrollment_number', $data['enrollment_number'])
            ->where('event_id', $data['event_id'])
            ->exists();

        if ($exists) {
            return back()->withInput()->with(
                'error',
                "Certificate already issued to enrollment #{$data['enrollment_number']} for this event."
            );
        }

        $event = Event::find($data['event_id']);
        $data['event_name'] = $event->name;

        $certificate = $this->certService->issue($data, auth()->user());

        if ($request->boolean('send_email')) {
            $this->certService->sendEmail($certificate);
        }

        return redirect()->route('teacher.certchain.certificates.show', $certificate)
            ->with('success', "Certificate {$certificate->certificate_id} issued and blockchain-recorded!");
    }

    // ── Bulk Issue ────────────────────────────────────────
    public function bulkCreate()
    {
        $events = Event::where('status', 'active')->orderBy('name')->get();
        $templates = CertificateTemplate::where('is_active', true)->get();
        $studentsJson = $this->getStudentsData();
        return view('certificates.bulk', compact('events', 'templates', 'studentsJson'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'template_id' => 'required|exists:certificate_templates,id',
            'students' => 'required|array|min:1',
        ]);

        $results = $this->certService->bulkIssue(
            $request->students,
            $request->event_id,
            $request->template_id,
            auth()->user()
        );

        if ($request->boolean('send_emails')) {
            foreach ($results['success'] as $cert) {
                $this->certService->sendEmail($cert);
            }
        }

        return redirect()->route('teacher.certchain.certificates.index')
            ->with('bulk_results', $results)
            ->with('success', count($results['success']) . ' certificates issued successfully!');
    }

    // ── Show / Download ───────────────────────────────────
    public function show(Certificate $certificate)
    {
        $certificate->load(['event', 'issuer', 'template', 'blockchainBlock']);
        $verification = $this->blockchain->verifyCertificate($certificate);
        return view('certificates.show', compact('certificate', 'verification'));
    }

    public function download(Certificate $certificate)
    {
        // Always regenerate fresh PDF from latest template
        $certificate->load(['event', 'issuer', 'template', 'blockchainBlock']);
        $this->certService->generatePDF($certificate);
        $certificate->refresh();

        return Storage::disk('public')->download(
            $certificate->pdf_path,
            "Certificate-{$certificate->certificate_id}.pdf"
        );
    }

    public function sendEmail(Certificate $certificate)
    {
        $sent = $this->certService->sendEmail($certificate);
        if ($sent) {
            return back()->with('success', 'Certificate emailed to ' . $certificate->student_email);
        }
        return back()->with('error', 'Failed to send email. Check mail configuration.');
    }

    // ── Revoke ────────────────────────────────────────────
    public function revoke(Request $request, Certificate $certificate)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $certificate->update([
            'status' => 'revoked',
            'revoke_reason' => $request->reason,
        ]);

        return back()->with('success', 'Certificate revoked successfully.');
    }
}
