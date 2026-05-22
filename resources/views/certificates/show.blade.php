@extends('teacher.layout')
@section('page_title','Certificate Details')
@section('page-title','Certificate Details')
@section('page-subtitle', $certificate->certificate_id)



@section('content')
<div class="flex justify-end mb-4"><a href="{{ route('teacher.certchain.certificates.download', $certificate) }}" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all text-sm">⬇ Download PDF</a>
@if(!$certificate->email_sent)
<form method="POST" action="{{ route('teacher.certchain.certificates.email', $certificate) }}" class="inline">
    @csrf
    <button class="bg-amber-500 text-white px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all text-sm ml-2">📧 Send Email</button>
</form>
@endif</div>
<div class="grid lg:grid-cols-3 gap-6">

    {{-- Left: Certificate Info --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Blockchain Verification Banner --}}
        @if($verification['valid'])
        <div class="flex items-center gap-4 p-4 bg-green-50 border border-green-200 rounded-xl">
            <div class="text-4xl">✅</div>
            <div>
                <p class="font-bold text-green-800">BLOCKCHAIN VERIFIED</p>
                <p class="text-sm text-green-600">This certificate is authentic and recorded on the blockchain ledger.</p>
            </div>
        </div>
        @else
        <div class="flex items-center gap-4 p-4 bg-red-50 border border-red-200 rounded-xl">
            <div class="text-4xl">🚨</div>
            <div>
                <p class="font-bold text-red-800">{{ $verification['status'] }}</p>
                <p class="text-sm text-red-600">{{ $verification['message'] }}</p>
            </div>
        </div>
        @endif

        {{-- Student Details --}}
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
            <h3 class="font-semibold text-on-surface font-bold mb-4">🎓 Student Information</h3>
            <div class="grid md:grid-cols-2 gap-y-4 gap-x-6 text-sm">
                @php
                $fields = [
                    'Student Name'      => $certificate->student_name,
                    'Enrollment No.'    => $certificate->enrollment_number,
                    'Email'             => $certificate->student_email,
                    'Branch'            => $certificate->student_branch ?? '—',
                    'Year'              => $certificate->student_year ?? '—',
                    'Achievement'       => $certificate->achievement,
                    'Issued Date'       => $certificate->issued_date?->format('d M Y'),
                    'Issued By'         => $certificate->issuer->name ?? '—',
                ];
                @endphp
                @foreach($fields as $label => $value)
                <div>
                    <p class="text-xs text-on-surface-variant opacity-70 mb-0.5">{{ $label }}</p>
                    <p class="font-medium text-on-surface font-bold">{{ $value }}</p>
                </div>
                @endforeach
            </div>
            @if($certificate->description)
            <div class="mt-4 pt-4 border-t border-outline-variant/10">
                <p class="text-xs text-on-surface-variant opacity-70 mb-1">Description</p>
                <p class="text-sm text-on-surface">{{ $certificate->description }}</p>
            </div>
            @endif
        </div>

        {{-- Event Details --}}
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
            <h3 class="font-semibold text-on-surface font-bold mb-4">📅 Event Information</h3>
            <div class="grid md:grid-cols-2 gap-y-4 text-sm">
                <div><p class="text-xs text-on-surface-variant opacity-70 mb-0.5">Event Name</p><p class="font-medium">{{ $certificate->event->name }}</p></div>
                <div><p class="text-xs text-on-surface-variant opacity-70 mb-0.5">Event Type</p><p class="font-medium">{{ $certificate->event->event_type }}</p></div>
                <div><p class="text-xs text-on-surface-variant opacity-70 mb-0.5">Date</p><p class="font-medium">{{ $certificate->event->event_date?->format('d M Y') }}</p></div>
                <div><p class="text-xs text-on-surface-variant opacity-70 mb-0.5">Venue</p><p class="font-medium">{{ $certificate->event->venue ?? '—' }}</p></div>
            </div>
        </div>
    </div>

        {{-- Right: Blockchain Block Info --}}
    <div class="space-y-5">

        {{-- Status Badge --}}
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5 text-center">
            <p class="text-xs text-on-surface-variant opacity-70 mb-2">Certificate Status</p>
            <span class="px-4 py-1.5 rounded-full font-semibold text-sm {{ $certificate->status === 'issued' ? 'badge-verified' : 'badge-revoked' }}">
                {{ strtoupper($certificate->status) }}
            </span>
            @if($certificate->email_sent)
            <p class="text-xs text-on-surface-variant opacity-70 mt-2">?? Email sent {{ $certificate->email_sent_at?->diffForHumans() }}</p>
            @endif
        </div>

        {{-- Blockchain Block --}}
        @if($block = $certificate->blockchainBlock)
        <div class="bg-primary text-on-primary rounded-3xl border border-outline-variant/10 shadow-lg p-5">
            <p class="text-xs opacity-70 mb-3 uppercase tracking-widest text-on-primary">Blockchain Block #{{ $block->block_index }}</p>
            <div class="space-y-3 text-xs font-mono">
                <div>
                    <p class="opacity-70">Block Hash</p>
                    <p class="text-amber-400 break-all">{{ $block->block_hash }}</p>
                </div>
                <div>
                    <p class="opacity-70">Previous Hash</p>
                    <p class="opacity-90 break-all">{{ substr($block->previous_hash, 0, 32) }}...</p>
                </div>
                <div>
                    <p class="opacity-70">Data Hash</p>
                    <p class="text-emerald-400 break-all">{{ substr($block->data_hash, 0, 32) }}...</p>
                </div>
                <div>
                    <p class="opacity-70">Mined At</p>
                    <p class="opacity-90">{{ $block->mined_at?->format('d M Y H:i:s') }}</p>
                </div>
            </div>
        </div>
        @endif

        {{-- Verify Link --}}
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5 text-center">
            <p class="text-xs text-on-surface-variant mb-2">Public Verification Link</p>
            <a href="{{ route('verify.certificate', $certificate->certificate_id) }}" target="_blank"
                class="text-xs text-primary break-all hover:underline">
                {{ route('verify.certificate', $certificate->certificate_id) }}
            </a>
        </div>

        {{-- Revoke --}}
        @if($certificate->status === 'issued')
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5">
            <h4 class="font-semibold text-red-700 text-sm mb-3">? Revoke Certificate</h4>
            <form method="POST" action="{{ route('teacher.certchain.certificates.revoke', $certificate) }}" onsubmit="return confirm('Are you sure you want to revoke this certificate?')">
                @csrf
                <textarea name="reason" required placeholder="Reason for revocation�" rows="2"
                    class="w-full border border-red-200 rounded-lg px-3 py-2 text-xs mb-2 resize-none focus:outline-none focus:ring-2 focus:ring-red-400"></textarea>
                <button class="w-full py-2 bg-red-600 text-white rounded-lg text-sm font-medium hover:bg-red-700 transition">Revoke Certificate</button>
            </form>
        </div>
        @elseif($certificate->status === 'revoked')
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5 border-l-4 border-red-400">
            <p class="text-sm font-semibold text-red-700">Revocation Reason</p>
            <p class="text-xs text-red-600 mt-1">{{ $certificate->revoke_reason }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
