<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CertificateService
{
    public function __construct(
        protected BlockchainService $blockchain
    ) {}

    public function issue(array $data, User $issuedBy): Certificate
    {
        $certId = $this->blockchain->generateCertificateId(
            strtoupper(substr($data['event_name'] ?? 'CERT', 0, 4))
        );

        $certificate = Certificate::create([
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

        $this->blockchain->mineBlock($certificate->fresh(['event', 'issuer']));

        $qrPath = $this->generateQRCode($certificate);
        $certificate->update(['qr_code_path' => $qrPath]);

        $pdfPath = $this->generatePDF($certificate->fresh(['event', 'issuer', 'template', 'blockchainBlock']));
        $certificate->update(['pdf_path' => $pdfPath]);

        return $certificate->fresh();
    }

    public function bulkIssue(array $studentsData, int $eventId, int $templateId, User $issuedBy): array
    {
        $results = ['success' => [], 'failed' => []];

        foreach ($studentsData as $student) {
            try {
                $student['event_id']    = $eventId;
                $student['template_id'] = $templateId;
                $student['event_name']  = Event::find($eventId)?->name ?? 'EVENT';
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

    public function generateQRCode(Certificate $certificate): string
    {
        $verifyUrl = route('verify.certificate', ['id' => $certificate->certificate_id]);
        $filename  = "qrcodes/{$certificate->certificate_id}.svg";

        $qr = QrCode::format('svg')
            ->size(150)
            ->errorCorrection('H')
            ->generate($verifyUrl);

        Storage::disk('public')->put($filename, $qr);
        return $filename;
    }

    public function generatePDF(Certificate $certificate): string
    {
        $event    = $certificate->event;
        $issuer   = $certificate->issuer;
        $block    = $certificate->blockchainBlock;

        $collegeName = config('app.college_name', env('COLLEGE_NAME', 'Your College'));
        $studentName = $certificate->student_name;
        $enrollment  = $certificate->enrollment_number;
        $branch      = $certificate->student_branch ?? '';
        $year        = $certificate->student_year ?? '';
        $eventName   = $event->name;
        $eventDate   = $event->event_date->format('d M Y');
        $venue       = $event->venue ?? '';
        $achievement = $certificate->achievement;
        $issuedDate  = $certificate->issued_date->format('d M Y');
        $issuedBy    = $issuer->name;
        $designation = $issuer->designation ?? '';
        $certId      = $certificate->certificate_id;
        $blockHash   = $block ? substr($block->block_hash, 0, 24) . '...' : '';

        // A4 Landscape: 297mm x 210mm
        $pdf = new \FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(false);

        // ── Outer Navy Border ──────────────────────────
        $pdf->SetFillColor(26, 58, 92);
        $pdf->Rect(0, 0, 297, 8, 'F');    // Top
        $pdf->Rect(0, 202, 297, 8, 'F'); // Bottom
        $pdf->Rect(0, 0, 8, 210, 'F');   // Left
        $pdf->Rect(289, 0, 8, 210, 'F'); // Right

        // ── Inner Gold Border ──────────────────────────
        $pdf->SetDrawColor(201, 168, 76);
        $pdf->SetLineWidth(0.5);
        $pdf->Rect(11, 11, 275, 188);

        // ── College Name ── Y=32 ───────────────────────
        $pdf->SetFont('Times', '', 8);
        $pdf->SetTextColor(26, 58, 92);
        $pdf->SetXY(0, 32);
        $pdf->Cell(297, 5, strtoupper($collegeName), 0, 1, 'C');

        // ── Gold Line Top ──────────────────────────────
        $pdf->SetDrawColor(201, 168, 76);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(100, 38, 197, 38);

        // ── Certificate Title ── Y=40 ──────────────────
        $pdf->SetFont('Times', 'B', 34);
        $pdf->SetTextColor(201, 168, 76);
        $pdf->SetXY(0, 40);
        $pdf->Cell(297, 14, 'Certificate', 0, 1, 'C');

        // ── Achievement ── Y=54 ───────────────────────
        $pdf->SetFont('Times', '', 8);
        $pdf->SetTextColor(136, 136, 136);
        $pdf->SetXY(0, 55);
        $pdf->Cell(297, 5, 'OF ' . strtoupper($achievement), 0, 1, 'C');

        // ── Gold Line Bottom ───────────────────────────
        $pdf->Line(80, 62, 217, 62);

        // ── Presented To ── Y=65 ──────────────────────
        $pdf->SetFont('Times', 'I', 10);
        $pdf->SetTextColor(153, 153, 153);
        $pdf->SetXY(0, 65);
        $pdf->Cell(297, 7, 'This is proudly presented to', 0, 1, 'C');

        // ── Student Name ── Y=73 ──────────────────────
        $pdf->SetFont('Times', 'B', 28);
        $pdf->SetTextColor(26, 58, 92);
        $pdf->SetXY(0, 73);
        $pdf->Cell(297, 13, $studentName, 0, 1, 'C');

        // Name underline
        $nameWidth = $pdf->GetStringWidth($studentName) + 20;
        $nameX     = (297 - $nameWidth) / 2;
        $pdf->SetDrawColor(201, 168, 76);
        $pdf->SetLineWidth(0.5);
        $pdf->Line($nameX, 86, $nameX + $nameWidth, 86);

        // ── Enrollment Info ── Y=90 ───────────────────
        $pdf->SetFont('Times', '', 9);
        $pdf->SetTextColor(85, 85, 85);
        $pdf->SetXY(0, 90);
        $infoLine = 'Enrollment No: ' . $enrollment . '   |   ' . $branch . '   |   ' . $year;
        $pdf->Cell(297, 6, $infoLine, 0, 1, 'C');

        // ── Body Text ── Y=100 ────────────────────────
        $pdf->SetFont('Times', '', 9);
        $pdf->SetTextColor(85, 85, 85);
        $pdf->SetXY(0, 100);
        $descriptionText = $certificate->description ? $certificate->description : 'for successfully participating in the';
        $pdf->Cell(297, 6, $descriptionText, 0, 1, 'C');

        $pdf->SetFont('Times', 'B', 11);
        $pdf->SetTextColor(26, 58, 92);
        $pdf->SetXY(0, 107);
        $pdf->Cell(297, 7, $eventName, 0, 1, 'C');

        $pdf->SetFont('Times', '', 9);
        $pdf->SetTextColor(85, 85, 85);
        $pdf->SetXY(0, 115);
        $pdf->Cell(297, 6, 'held on ' . $eventDate . ' at ' . $venue, 0, 1, 'C');

        // ── Signature Lines ── Y=145 ──────────────────
        $sigY = 148;
        $pdf->SetDrawColor(170, 170, 170);
        $pdf->SetLineWidth(0.2);
        $pdf->Line(50, $sigY, 140, $sigY);    // Left
        $pdf->Line(157, $sigY, 247, $sigY);   // Right

        // Left: Issued By
        $pdf->SetFont('Times', 'B', 9);
        $pdf->SetTextColor(51, 51, 51);
        $pdf->SetXY(50, $sigY + 2);
        $pdf->Cell(90, 5, $issuedBy, 0, 0, 'C');

        // Right: Date
        $pdf->SetXY(157, $sigY + 2);
        $pdf->Cell(90, 5, 'Date: ' . $issuedDate, 0, 0, 'C');

        // Left: Designation
        $pdf->SetFont('Times', 'I', 7);
        $pdf->SetTextColor(102, 102, 102);
        $pdf->SetXY(50, $sigY + 8);
        $pdf->Cell(90, 4, $designation, 0, 0, 'C');

        // ── Bottom Info ── Y=196-199 ──────────────────
        $verifyUrl = route('verify.certificate', ['id' => $certificate->certificate_id]);

        $pdf->SetXY(14, 196);
        $pdf->SetFont('Times', '', 5.5);
        $pdf->SetTextColor(170, 170, 170);
        $pdf->Cell(269, 3, 'Certificate ID: ' . $certId . '   |   Block Hash: ' . $blockHash, 0, 0, 'L');

        $pdf->SetXY(14, 199);
        $pdf->Cell(269, 3, 'Verify at: ' . $verifyUrl, 0, 0, 'L');

        // ── Save PDF ───────────────────────────────────
        $filename = "certificates/{$certificate->certificate_id}.pdf";
        Storage::disk('public')->put($filename, $pdf->Output('S'));

        return $filename;
    }

    public function sendEmail(Certificate $certificate): bool
    {
        try {
            $pdfPath = Storage::disk('public')->path($certificate->pdf_path);

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