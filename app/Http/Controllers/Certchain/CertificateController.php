<?php

namespace App\Http\Controllers\Certchain;

use App\Models\CertchainCertificate as Certificate;
use App\Models\CertchainTemplate as CertificateTemplate;
use App\Models\CertchainEvent as Event;
use App\Services\BlockchainService;
use App\Services\CertificateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends \App\Http\Controllers\Controller
{
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
        if (auth()->user()->role !== 'admin') {
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
        $eventsQuery = Event::orderBy('name');
        if (auth()->user()->role !== 'admin') {
            $eventsQuery->where('created_by', auth()->id());
        }
        $events = $eventsQuery->get();

        return view('certchain.certificates.index', compact('certificates', 'events'));
    }

    // ── Issue Single ──────────────────────────────────────
    public function create()
    {
        $eventsQuery = Event::where('status', 'active')->orderBy('name');
        if (auth()->user()->role !== 'admin') {
            $eventsQuery->where('created_by', auth()->id());
        }
        $events = $eventsQuery->get();
        $templates = CertificateTemplate::where('is_active', true)->get();
        return view('certchain.certificates.create', compact('events', 'templates'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'event_id' => 'required|exists:certchain_events,id',
            'template_id' => 'required|exists:certchain_templates,id',
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
        $eventsQuery = Event::where('status', 'active')->orderBy('name');
        if (auth()->user()->role !== 'admin') {
            $eventsQuery->where('created_by', auth()->id());
        }
        $events = $eventsQuery->get();
        $templates = CertificateTemplate::where('is_active', true)->get();
        return view('certchain.certificates.bulk', compact('events', 'templates'));
    }

    public function bulkStore(Request $request)
    {
        $request->validate([
            'event_id' => 'required|exists:certchain_events,id',
            'template_id' => 'required|exists:certchain_templates,id',
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
        return view('certchain.certificates.show', compact('certificate', 'verification'));
    }

    public function download(Certificate $certificate)
    {
        if (!$certificate->pdf_path || !\Illuminate\Support\Facades\File::exists(public_path($certificate->pdf_path))) {
            // Regenerate
            $certificate->load(['event', 'issuer', 'template', 'blockchainBlock']);
            $this->certService->generatePDF($certificate);
            $certificate->refresh();
        }

        return response()->download(
            public_path($certificate->pdf_path),
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
