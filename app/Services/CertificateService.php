<?php

namespace App\Services;

use App\Models\CertchainCertificate;
use App\Models\CertchainTemplate;
use App\Models\CertchainEvent;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\File;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateService
{
    public function __construct(
        protected BlockchainService $blockchain
    ) {}

    /**
     * Issue a single certificate — full pipeline:
     * create record → mine block → generate QR → generate PDF → send email
     */
    public function issue(array $data, User $issuedBy): CertchainCertificate
    {
        // 1. Generate unique Certificate ID
        $certId = $this->blockchain->generateCertificateId(
            strtoupper(substr($data['event_name'] ?? 'CERT', 0, 4))
        );

        // 2. Create certificate record
        $certificate = CertchainCertificate::create([
            'certificate_id'    => $certId,
            'event_id'          => $data['event_id'],
            'template_id'       => $data['template_id'],
            'issued_by'         => $issuedBy->id,
            'student_name'      => $data['student_name'],
            'student_email'     => $data['student_email'],
            'enrollment_number' => $data['enrollment_number'],
            'student_branch'    => $data['student_branch'] ?? null,
            'student_year'      => $data['student_year'] ?? null,
            'achievement'       => $data['achievement'] ?? 'Participation',
            'description'       => $data['description'] ?? null,
            'issued_date'       => $data['issued_date'] ?? now()->toDateString(),
            'status'            => 'issued',
        ]);

        // 3. Mine blockchain block
        $this->blockchain->mineBlock($certificate->fresh(['event', 'issuer']));

        // 4. Generate QR code
        $qrPath = $this->generateQRCode($certificate);
        $certificate->update(['qr_code_path' => $qrPath]);

        // 5. Generate PDF
        $pdfPath = $this->generatePDF($certificate->fresh(['event', 'issuer', 'template', 'blockchainBlock']));
        $certificate->update(['pdf_path' => $pdfPath]);

        return $certificate->fresh();
    }

    /**
     * Issue certificates in bulk from CSV-like array
     */
    public function bulkIssue(array $studentsData, int $eventId, int $templateId, User $issuedBy): array
    {
        $results = ['success' => [], 'failed' => []];

        foreach ($studentsData as $student) {
            try {
                $student['event_id'] = $eventId;
                $student['template_id'] = $templateId;
                $student['event_name'] = CertchainEvent::find($eventId)?->name ?? 'EVENT';
                $cert = $this->issue($student, $issuedBy);
                $results['success'][] = $cert;
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'student' => $student['student_name'] ?? 'Unknown',
                    'error'   => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Generate QR code image pointing to verification URL
     */
    protected function generateQRCode(CertchainCertificate $certificate): string
    {
        $verifyUrl = route('verify.certificate', ['id' => $certificate->certificate_id]);
        $filename = "generate-pdf/qrcodes/{$certificate->certificate_id}.svg";
        $directory = public_path('generate-pdf/qrcodes');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $qr = QrCode::format('svg')
            ->size(200)
            ->errorCorrection('H')
            ->generate($verifyUrl);

        File::put(public_path($filename), $qr);
        return $filename;
    }

    /**
     * Generate PDF certificate using template
     */
    public function generatePDF(CertchainCertificate $certificate): string
    {
        $template = $certificate->template;
        $event    = $certificate->event;
        $issuer   = $certificate->issuer;
        $block    = $certificate->blockchainBlock;

        $qrCodeData = null;
        if ($certificate->qr_code_path && File::exists(public_path($certificate->qr_code_path))) {
            $qrCodeData = File::get(public_path($certificate->qr_code_path));
        }

        // Render template with real data
        $rendered = $template->render([
            'student_name'      => $certificate->student_name,
            'enrollment_number' => $certificate->enrollment_number,
            'student_branch'    => $certificate->student_branch ?? '',
            'student_year'      => $certificate->student_year ?? '',
            'event_name'        => $event->name,
            'event_date'        => $event->event_date->format('d M Y'),
            'event_type'        => $event->event_type,
            'venue'             => $event->venue ?? '',
            'achievement'       => $certificate->achievement,
            'description'       => $certificate->description ?? '',
            'issued_date'       => $certificate->issued_date->format('d M Y'),
            'issued_by'         => $issuer->name,
            'issuer_designation'=> $issuer->designation ?? '',
            'certificate_id'    => $certificate->certificate_id,
            'block_hash'        => $block ? substr($block->block_hash, 0, 16) . '...' : '',
            'college_name'      => config('app.college_name', env('COLLEGE_NAME', 'Your College')),
            'qr_code'           => $qrCodeData ?? '',
        ]);

        $pdf = Pdf::loadHTML($rendered)
            ->setPaper('a4', 'landscape')
            ->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled'      => true,
                'defaultFont'          => 'serif',
            ]);

        $filename = "generate-pdf/certificates/{$certificate->certificate_id}.pdf";
        $directory = public_path('generate-pdf/certificates');

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        File::put(public_path($filename), $pdf->output());

        return $filename;
    }

    /**
     * Send certificate email to student
     */
    public function sendEmail(CertchainCertificate $certificate): bool
    {
        try {
            $pdfPath = public_path($certificate->pdf_path);

            Mail::send('emails.certificate', [
                'certificate' => $certificate,
                'event'       => $certificate->event,
            ], function ($message) use ($certificate, $pdfPath) {
                $message->to($certificate->student_email, $certificate->student_name)
                    ->subject("Your Certificate - {$certificate->event->name}")
                    ->attach($pdfPath, [
                        'as'   => "Certificate-{$certificate->certificate_id}.pdf",
                        'mime' => 'application/pdf',
                    ]);
            });

            $certificate->update([
                'email_sent'    => true,
                'email_sent_at' => now(),
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send certificate email: " . $e->getMessage());
            return false;
        }
    }
}
