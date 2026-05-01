@extends('layouts.app')
@section('title','Dashboard')
@section('page-title','My Dashboard')
@section('page-subtitle','Welcome back, {{ auth()->user()->name }}')

@section('header-actions')
<a href="{{ route('certificates.create') }}" class="btn-gold text-sm">+ Issue Certificate</a>
@endsection

@section('content')
{{-- Stats --}}
<div class="grid grid-cols-3 gap-4 mb-6">
    <div class="card p-5 text-center">
        <p class="text-3xl font-bold text-primary">{{ $stats['my_events'] }}</p>
        <p class="text-sm text-gray-500 mt-1">My Events</p>
    </div>
    <div class="card p-5 text-center">
        <p class="text-3xl font-bold text-green-600">{{ $stats['my_certificates'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Certificates Issued</p>
    </div>
    <div class="card p-5 text-center">
        <p class="text-3xl font-bold text-blue-600">{{ $stats['emails_sent'] }}</p>
        <p class="text-sm text-gray-500 mt-1">Emails Sent</p>
    </div>
</div>

<div class="grid lg:grid-cols-2 gap-6">
    {{-- Recent Certificates --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Recently Issued</h3>
            <a href="{{ route('certificates.index') }}" class="text-xs text-blue-600 hover:underline">View all →</a>
        </div>
        <div class="space-y-3">
            @forelse($recentCerts as $cert)
            <a href="{{ route('certificates.show', $cert) }}" class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0 hover:bg-gray-50 -mx-2 px-2 rounded">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $cert->student_name }}</p>
                    <p class="text-xs text-gray-400">{{ $cert->event->name ?? '' }} &bull; {{ $cert->issued_date?->format('d M Y') }}</p>
                </div>
                <span class="text-xs text-blue-600">View →</span>
            </a>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">No certificates issued yet.<br>
                <a href="{{ route('certificates.create') }}" class="text-blue-600 hover:underline">Issue your first one →</a>
            </p>
            @endforelse
        </div>
    </div>

    {{-- My Events --}}
    <div class="card p-5">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">My Events</h3>
            <a href="{{ route('events.create') }}" class="text-xs text-blue-600 hover:underline">+ New event</a>
        </div>
        <div class="space-y-3">
            @forelse($myEvents as $event)
            <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $event->name }}</p>
                    <p class="text-xs text-gray-400">{{ $event->event_date?->format('d M Y') }} &bull; {{ $event->event_type }}</p>
                </div>
                <span class="px-2 py-0.5 rounded-full text-xs {{ $event->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                    {{ ucfirst($event->status) }}
                </span>
            </div>
            @empty
            <p class="text-sm text-gray-400 text-center py-6">No events created yet.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- Quick Actions --}}
<div class="mt-6 card p-5">
    <h3 class="font-semibold text-gray-800 mb-4">Quick Actions</h3>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <a href="{{ route('events.create') }}" class="flex flex-col items-center gap-2 p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition text-center">
            <span class="text-2xl">📅</span>
            <span class="text-xs font-medium text-blue-800">Create Event</span>
        </a>
        <a href="{{ route('certificates.create') }}" class="flex flex-col items-center gap-2 p-4 bg-green-50 rounded-xl hover:bg-green-100 transition text-center">
            <span class="text-2xl">📜</span>
            <span class="text-xs font-medium text-green-800">Issue Certificate</span>
        </a>
        <a href="{{ route('certificates.bulk') }}" class="flex flex-col items-center gap-2 p-4 bg-yellow-50 rounded-xl hover:bg-yellow-100 transition text-center">
            <span class="text-2xl">📦</span>
            <span class="text-xs font-medium text-yellow-800">Bulk Issue</span>
        </a>
        <a href="{{ route('verify.index') }}" target="_blank" class="flex flex-col items-center gap-2 p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition text-center">
            <span class="text-2xl">🔍</span>
            <span class="text-xs font-medium text-purple-800">Verify Certificate</span>
        </a>
    </div>
</div>
@endsection
