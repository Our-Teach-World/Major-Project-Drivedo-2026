@extends('teacher.layout')
@section('title','Certificates')
@section('page-title','Certificates')
@section('page-subtitle','All issued blockchain-recorded certificates')

@section('header-actions')
<a href="{{ route('teacher.certchain.certificates.bulk') }}" class="btn-gold text-sm mr-2">📦 Bulk Issue</a>
<a href="{{ route('teacher.certchain.certificates.create') }}" class="btn-primary text-sm">+ Issue New</a>
@endsection

@section('content')
{{-- Filters --}}
<form method="GET" class="card p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-48">
        <label class="block text-xs text-gray-500 mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, enrollment no., cert ID…"
            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    </div>
    <div>
        <label class="block text-xs text-gray-500 mb-1">Event</label>
        <select name="event_id" class="border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">All Events</option>
            @foreach($events as $event)
            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="btn-primary text-sm">Filter</button>
    <a href="{{ route('teacher.certchain.certificates.index') }}" class="text-sm text-gray-400 hover:text-gray-600">Clear</a>
</form>

{{-- Table --}}
<div class="card overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 border-b border-gray-100">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Certificate ID</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Student</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Event</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Achievement</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Blockchain</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($certificates as $cert)
            <tr class="hover:bg-gray-50 transition">
                <td class="px-5 py-3">
                    <p class="font-mono text-xs text-blue-600">{{ $cert->certificate_id }}</p>
                    <p class="text-xs text-gray-400">{{ $cert->issued_date?->format('d M Y') }}</p>
                </td>
                <td class="px-5 py-3">
                    <p class="font-medium text-gray-800">{{ $cert->student_name }}</p>
                    <p class="text-xs text-gray-400">{{ $cert->enrollment_number }}</p>
                </td>
                <td class="px-5 py-3">
                    <p class="text-gray-700">{{ Str::limit($cert->event->name ?? '—', 30) }}</p>
                </td>
                <td class="px-5 py-3 text-gray-600">{{ $cert->achievement }}</td>
                <td class="px-5 py-3">
                    @if($cert->blockchainBlock)
                    <span class="text-green-600 text-xs flex items-center gap-1">⛓ Block #{{ $cert->blockchainBlock->block_index }}</span>
                    @else
                    <span class="text-red-500 text-xs">⚠ No block</span>
                    @endif
                </td>
                <td class="px-5 py-3">
                    <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $cert->status === 'issued' ? 'badge-verified' : 'badge-revoked' }}">
                        {{ ucfirst($cert->status) }}
                    </span>
                    @if($cert->email_sent) <span class="text-xs text-gray-400 ml-1">📧</span> @endif
                </td>
                <td class="px-5 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('teacher.certchain.certificates.show', $cert) }}" class="text-xs text-blue-600 hover:underline">View</a>
                        <a href="{{ route('teacher.certchain.certificates.download', $cert) }}" class="text-xs text-gray-500 hover:underline">PDF</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-10 text-center text-gray-400">
                No certificates found.
                <a href="{{ route('teacher.certchain.certificates.create') }}" class="text-blue-600 hover:underline ml-1">Issue one →</a>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-gray-100">
        {{ $certificates->links() }}
    </div>
</div>
@endsection
