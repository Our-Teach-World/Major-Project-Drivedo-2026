@extends('layouts.app')
@section('title','Admin Dashboard')
@section('page-title','Admin Dashboard')
@section('page-subtitle','System overview and blockchain status')

@section('header-actions')
<a href="{{ route('certificates.create') }}" class="btn-primary text-sm">+ Issue Certificate</a>
@endsection

@section('content')
{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    @php
    $statCards = [
        ['label'=>'Total Users',       'value'=>$stats['total_users'],        'icon'=>'👥', 'color'=>'blue'],
        ['label'=>'Events',            'value'=>$stats['total_events'],        'icon'=>'📅', 'color'=>'purple'],
        ['label'=>'Certificates',      'value'=>$stats['total_certificates'],  'icon'=>'📜', 'color'=>'green'],
        ['label'=>'Blockchain Blocks', 'value'=>$stats['total_blocks'],        'icon'=>'⛓',  'color'=>'yellow'],
        ['label'=>'Emails Sent',       'value'=>$stats['emails_sent'],         'icon'=>'📧', 'color'=>'teal'],
        ['label'=>'Revoked',           'value'=>$stats['revoked'],             'icon'=>'🚫', 'color'=>'red'],
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="card p-5">
        <p class="text-2xl mb-2">{{ $card['icon'] }}</p>
        <p class="text-2xl font-bold text-gray-800">{{ number_format($card['value']) }}</p>
        <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Blockchain Status --}}
<div class="card p-5 mb-6 flex items-center gap-4 {{ $chainStatus['valid'] ? 'border-l-4 border-green-500' : 'border-l-4 border-red-500' }}">
    <span class="text-3xl">{{ $chainStatus['valid'] ? '✅' : '🚨' }}</span>
    <div class="flex-1">
        <p class="font-semibold text-gray-800">Blockchain Chain Integrity: 
            <span class="{{ $chainStatus['valid'] ? 'text-green-600' : 'text-red-600' }}">
                {{ $chainStatus['valid'] ? 'VALID & INTACT' : 'COMPROMISED' }}
            </span>
        </p>
        <p class="text-sm text-gray-500">{{ $chainStatus['total_blocks'] }} blocks in chain
            @if(!$chainStatus['valid']) — {{ count($chainStatus['errors']) }} error(s) detected @endif
        </p>
    </div>
    <a href="{{ route('admin.blockchain') }}" class="btn-primary text-sm">View Ledger</a>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    {{-- Recent Certificates --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Recent Certificates</h3>
            <a href="{{ route('certificates.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
        </div>
        <div class="space-y-3">
            @forelse($recentCertificates as $cert)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800 truncate">{{ $cert->student_name }}</p>
                    <p class="text-xs text-gray-400">{{ $cert->certificate_id }} &bull; {{ $cert->event->name ?? 'N/A' }}</p>
                </div>
                <span class="ml-2 px-2 py-0.5 rounded-full text-xs font-medium {{ $cert->status === 'issued' ? 'badge-verified' : 'badge-revoked' }}">
                    {{ ucfirst($cert->status) }}
                </span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-4">No certificates issued yet.</p>
            @endforelse
        </div>
    </div>

    {{-- Monthly Chart --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Certificates This Year</h3>
        <div class="space-y-2">
            @php
            $months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
            $max = max(array_values($monthlyStats) ?: [1]);
            @endphp
            @foreach($months as $i => $month)
            @php $count = $monthlyStats[$i+1] ?? 0; $width = $max > 0 ? round(($count/$max)*100) : 0; @endphp
            <div class="flex items-center gap-3">
                <span class="text-xs text-gray-400 w-7">{{ $month }}</span>
                <div class="flex-1 bg-gray-100 rounded-full h-2">
                    <div class="bg-blue-600 h-2 rounded-full transition-all" style="width:{{ $width }}%"></div>
                </div>
                <span class="text-xs text-gray-500 w-6 text-right">{{ $count }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
