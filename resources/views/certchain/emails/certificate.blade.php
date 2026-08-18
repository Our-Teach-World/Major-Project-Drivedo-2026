<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
body { font-family: Arial, sans-serif; background: #f5f5f5; margin: 0; padding: 20px; }
.container { max-width: 600px; margin: 0 auto; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,.1); }
.header { background: linear-gradient(135deg, #1a3a5c, #2a5298); padding: 32px; text-align: center; }
.header h1 { color: #c9a84c; font-size: 28px; margin: 0; }
.header p { color: rgba(255,255,255,.7); font-size: 13px; margin: 4px 0 0; }
.body { padding: 32px; }
.body h2 { color: #1a3a5c; margin-top: 0; }
.info-box { background: #f8fafc; border-radius: 8px; padding: 16px; margin: 16px 0; }
.info-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px solid #e5e7eb; font-size: 13px; }
.info-row:last-child { border: none; }
.info-row .label { color: #6b7280; }
.info-row .value { color: #111827; font-weight: 500; }
.blockchain-badge { background: linear-gradient(135deg, #0f2139, #1a3a5c); color: white; border-radius: 8px; padding: 12px 16px; margin: 16px 0; font-size: 12px; }
.blockchain-badge .hash { font-family: monospace; color: #fbbf24; word-break: break-all; font-size: 11px; }
.verify-btn { display: block; text-align: center; background: #1a3a5c; color: white; padding: 12px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; margin: 20px 0; }
.footer { background: #f8fafc; padding: 16px 32px; text-align: center; font-size: 11px; color: #9ca3af; }
</style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>⛓ CertChain</h1>
        <p>Blockchain Certificate System — {{ config('app.college_name', env('COLLEGE_NAME','Your College')) }}</p>
    </div>
    <div class="body">
        <h2>Congratulations, {{ $certificate->student_name }}! 🎉</h2>
        <p style="color:#374151; font-size:14px; line-height:1.7;">
            Your certificate for <strong>{{ $event->name }}</strong> has been successfully issued and recorded on our blockchain ledger. The PDF certificate is attached to this email.
        </p>

        <div class="info-box">
            <div class="info-row"><span class="label">Certificate ID</span><span class="value">{{ $certificate->certificate_id }}</span></div>
            <div class="info-row"><span class="label">Student Name</span><span class="value">{{ $certificate->student_name }}</span></div>
            <div class="info-row"><span class="label">Enrollment No.</span><span class="value">{{ $certificate->enrollment_number }}</span></div>
            <div class="info-row"><span class="label">Achievement</span><span class="value">{{ $certificate->achievement }}</span></div>
            <div class="info-row"><span class="label">Event</span><span class="value">{{ $event->name }}</span></div>
            <div class="info-row"><span class="label">Event Date</span><span class="value">{{ $event->event_date?->format('d M Y') }}</span></div>
            <div class="info-row"><span class="label">Issued Date</span><span class="value">{{ $certificate->issued_date?->format('d M Y') }}</span></div>
            <div class="info-row"><span class="label">Issued By</span><span class="value">{{ $certificate->issuer->name ?? '' }}</span></div>
        </div>

        @if($block = $certificate->blockchainBlock)
        <div class="blockchain-badge">
            <p style="margin:0 0 4px;font-weight:600;">⛓ Blockchain Record — Block #{{ $block->block_index }}</p>
            <p class="hash">{{ $block->block_hash }}</p>
        </div>
        @endif

        <a href="{{ route('verify.certificate', $certificate->certificate_id) }}" class="verify-btn">
            🔍 Verify This Certificate Online
        </a>

        <p style="font-size:12px; color:#6b7280;">You can also verify by entering your Enrollment Number (<strong>{{ $certificate->enrollment_number }}</strong>) at our verification portal.</p>
    </div>
    <div class="footer">
        <p>This is an auto-generated email from CertChain — {{ config('app.college_name', env('COLLEGE_NAME','Your College')) }}</p>
        <p>Please do not reply to this email.</p>
    </div>
</div>
</body>
</html>
