<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Result — CertChain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen bg-gray-50">

{{-- Top Bar --}}
<div class="py-4 px-6" style="background: linear-gradient(90deg, #0f2139, #1a3a5c);">
    <div class="max-w-3xl mx-auto flex items-center justify-between">
        <div class="flex items-center gap-3 text-white">
            <span class="text-2xl">⛓</span>
            <div>
                <p class="font-bold text-sm" style="font-family:'Space Grotesk',sans-serif;">CertChain</p>
                <p class="text-white/50 text-xs">Certificate Verification</p>
            </div>
        </div>
        <a href="{{ route('verify.index') }}" class="text-white/70 hover:text-white text-sm">← Verify Another</a>
    </div>
</div>

<div class="max-w-3xl mx-auto px-6 py-8">

    {{-- Status Banner --}}
    @if($verification['valid'])
    <div class="rounded-2xl p-6 mb-6 flex items-center gap-5" style="background: linear-gradient(135deg,#16a34a,#15803d); color:white;">
        <div class="text-5xl">✅</div>
        <div>
            <p class="text-xl font-bold" style="font-family:'Space Grotesk',sans-serif;">CERTIFICATE VERIFIED</p>
            <p class="text-green-100 text-sm mt-1">This certificate is authentic and recorded on the blockchain ledger.</p>
            <p class="text-green-200 text-xs mt-2">Block #{{ $verification['block']->block_index ?? '' }} &bull; Mined {{ $verification['block']->mined_at?->format('d M Y H:i') ?? '' }}</p>
        </div>
    </div>
    @elseif($verification['status'] === 'REVOKED')
    <div class="rounded-2xl p-6 mb-6 flex items-center gap-5 bg-red-600 text-white">
        <div class="text-5xl">🚫</div>
        <div>
            <p class="text-xl font-bold">CERTIFICATE REVOKED</p>
            <p class="text-red-100 text-sm mt-1">This certificate has been officially revoked and is no longer valid.</p>
        </div>
    </div>
    @else
    <div class="rounded-2xl p-6 mb-6 flex items-center gap-5 bg-red-600 text-white">
        <div class="text-5xl">🚨</div>
        <div>
            <p class="text-xl font-bold">VERIFICATION FAILED — {{ $verification['status'] }}</p>
            <p class="text-red-100 text-sm mt-1">{{ $verification['message'] }}</p>
        </div>
    </div>
    @endif

    <div class="grid md:grid-cols-2 gap-5">

        {{-- Student & Certificate Details --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-4 text-sm uppercase tracking-wide">Certificate Details</h3>
            @php
            $details = [
                'Certificate ID'   => $certificate->certificate_id,
                'Student Name'     => $certificate->student_name,
                'Enrollment No.'   => $certificate->enrollment_number,
                'Branch'           => $certificate->student_branch ?? '—',
                'Year'             => $certificate->student_year ?? '—',
                'Achievement'      => $certificate->achievement,
                'Issued Date'      => $certificate->issued_date?->format('d M Y'),
            ];
            @endphp
            <div class="space-y-3">
                @foreach($details as $label => $value)
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-400">{{ $label }}</span>
                    <span class="text-xs font-medium text-gray-800 text-right max-w-40">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Event & Issuer --}}
        <div class="bg-white rounded-2xl p-6 shadow-sm">
            <h3 class="font-semibold text-gray-800 mb-4 text-sm uppercase tracking-wide">Event Information</h3>
            @php
            $eventDetails = [
                'Event Name'  => $certificate->event->name,
                'Event Type'  => $certificate->event->event_type,
                'Event Date'  => $certificate->event->event_date?->format('d M Y'),
                'Venue'       => $certificate->event->venue ?? '—',
                'Department'  => $certificate->event->department ?? '—',
                'Issued By'   => $certificate->issuer->name,
                'Designation' => $certificate->issuer->designation ?? '—',
            ];
            @endphp
            <div class="space-y-3">
                @foreach($eventDetails as $label => $value)
                <div class="flex justify-between items-start">
                    <span class="text-xs text-gray-400">{{ $label }}</span>
                    <span class="text-xs font-medium text-gray-800 text-right max-w-40">{{ $value }}</span>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Blockchain Block --}}
        @if($block = $certificate->blockchainBlock)
        <div class="md:col-span-2 rounded-2xl p-6 text-white" style="background: linear-gradient(135deg,#0f2139,#1a3a5c);">
            <h3 class="font-semibold mb-4 text-sm uppercase tracking-wide text-white/60">⛓ Blockchain Record — Block #{{ $block->block_index }}</h3>
            <div class="grid md:grid-cols-2 gap-4 font-mono text-xs">
                <div>
                    <p class="text-white/40 mb-1">Block Hash</p>
                    <p class="text-yellow-300 break-all">{{ $block->block_hash }}</p>
                </div>
                <div>
                    <p class="text-white/40 mb-1">Data Hash</p>
                    <p class="text-green-300 break-all">{{ $block->data_hash }}</p>
                </div>
                <div>
                    <p class="text-white/40 mb-1">Previous Hash</p>
                    <p class="text-white/60 break-all">{{ $block->previous_hash }}</p>
                </div>
                <div>
                    <p class="text-white/40 mb-1">Mined At</p>
                    <p class="text-white/80">{{ $block->mined_at?->format('d M Y H:i:s') }} UTC</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <div class="mt-6 text-center">
        <a href="{{ route('verify.index') }}" class="inline-block px-6 py-2.5 bg-blue-900 text-white rounded-xl text-sm font-medium hover:bg-blue-800 transition">
            ← Verify Another Certificate
        </a>
    </div>
</div>

</body>
</html>
