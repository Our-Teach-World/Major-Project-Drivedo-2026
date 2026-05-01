@extends('layouts.app')
@section('title','Blockchain Ledger')
@section('page-title','Blockchain Ledger')
@section('page-subtitle','Immutable chain of all issued certificates')

@section('content')
{{-- Chain Status --}}
<div class="card p-5 mb-6 flex items-center gap-4 {{ $chainValid['valid'] ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500' }}">
    <span class="text-3xl">{{ $chainValid['valid'] ? '🔗' : '🚨' }}</span>
    <div class="flex-1">
        <p class="font-bold text-gray-800">
            Chain Status: <span class="{{ $chainValid['valid'] ? 'text-green-600' : 'text-red-600' }}">
                {{ $chainValid['valid'] ? 'VALID — All blocks intact' : 'CHAIN COMPROMISED' }}
            </span>
        </p>
        <p class="text-sm text-gray-500">{{ $chainValid['total_blocks'] }} blocks &bull; Algorithm: SHA-256 &bull; Type: Simulated Hash-Chain</p>
        @if(!$chainValid['valid'])
        @foreach($chainValid['errors'] as $err)
        <p class="text-xs text-red-600 mt-1">⚠ {{ $err }}</p>
        @endforeach
        @endif
    </div>
</div>

{{-- Chain Visualization (last 6 blocks) --}}
<div class="card p-5 mb-6">
    <h3 class="font-semibold text-gray-800 mb-4">Chain Visualization</h3>
    <div class="flex items-center gap-1 overflow-x-auto pb-2">
        @php $recent = $blocks->take(6)->reverse(); @endphp
        <div class="flex-shrink-0 w-24 h-16 rounded-lg bg-gray-200 flex items-center justify-center text-xs text-gray-500 font-mono">
            GENESIS<br><span class="text-gray-400">0000...0000</span>
        </div>
        @foreach($recent as $block)
        <div class="flex-shrink-0 text-gray-400 text-lg">→</div>
        <div class="flex-shrink-0 rounded-lg p-2 text-white text-center min-w-28" style="background: linear-gradient(135deg,#1a3a5c,#2a5298)">
            <p class="text-xs font-semibold text-yellow-300">Block #{{ $block->block_index }}</p>
            <p class="text-xs text-white/50 font-mono mt-1">{{ substr($block->block_hash, 0, 8) }}...</p>
            <p class="text-white/40 text-xs">{{ $block->mined_at?->format('d M') }}</p>
        </div>
        @endforeach
        <div class="flex-shrink-0 text-gray-400 text-lg">→ ∞</div>
    </div>
</div>

{{-- Full Table --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">#</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Certificate ID</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Student</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Block Hash</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Prev Hash</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Integrity</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500">Mined</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50 font-mono">
            @foreach($blocks as $block)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3 text-yellow-600 font-semibold">{{ $block->block_index }}</td>
                <td class="px-5 py-3">
                    <a href="{{ route('verify.certificate', $block->certificate_uid) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                        {{ $block->certificate_uid }}
                    </a>
                </td>
                <td class="px-5 py-3 text-xs text-gray-600 font-sans">
                    {{ $block->certificate->student_name ?? '—' }}<br>
                    <span class="text-gray-400">{{ $block->certificate->enrollment_number ?? '' }}</span>
                </td>
                <td class="px-5 py-3 text-xs text-yellow-700">{{ substr($block->block_hash, 0, 20) }}...</td>
                <td class="px-5 py-3 text-xs text-gray-400">{{ substr($block->previous_hash, 0, 20) }}...</td>
                <td class="px-5 py-3">
                    @if($block->isIntact())
                    <span class="text-green-600 text-xs">✅ Intact</span>
                    @else
                    <span class="text-red-600 text-xs">🚨 Tampered!</span>
                    @endif
                </td>
                <td class="px-5 py-3 text-xs text-gray-400 font-sans">{{ $block->mined_at?->format('d M Y H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $blocks->links() }}
    </div>
</div>
@endsection
