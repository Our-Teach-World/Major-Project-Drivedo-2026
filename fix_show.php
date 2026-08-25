<?php
$file = "resources/views/certificates/show.blade.php";
$content = file_get_contents($file);

$start = strpos($content, "{{-- Right: Blockchain Block Info --}}");
$end = strpos($content, "</div>\n</div>\n@endsection");

$newRight = <<<'HTML'
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
                <textarea name="reason" required placeholder="Reason for revocation…" rows="2"
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
HTML;

$finalContent = substr($content, 0, $start) . $newRight . "\n</div>\n@endsection\n";
file_put_contents($file, $finalContent);
echo "Fixed show.blade.php\n";

