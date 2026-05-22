@extends('teacher.layout')
@section('page_title','Certificates')
@section('page-title','Certificates')
@section('page-subtitle','All issued blockchain-recorded certificates')



@section('content')
<div class="flex justify-end mb-4"><a href="{{ route('teacher.certchain.certificates.bulk') }}" class="bg-amber-500 text-white px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all text-sm mr-2">📦 Bulk Issue</a>
<a href="{{ route('teacher.certchain.certificates.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all text-sm">+ Issue New</a></div>
{{-- Filters --}}
<form method="GET" class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-4 mb-5 flex flex-wrap gap-3 items-end">
    <div class="flex-1 min-w-48">
        <label class="block text-xs text-on-surface-variant mb-1">Search</label>
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Name, enrollment no., cert ID…"
            class="w-full border border-outline-variant/20 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
    </div>
    <div>
        <label class="block text-xs text-on-surface-variant mb-1">Event</label>
        <select name="event_id" class="border border-outline-variant/20 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            <option value="">All Events</option>
            @foreach($events as $event)
            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
            @endforeach
        </select>
    </div>
    <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all text-sm">Filter</button>
    <a href="{{ route('teacher.certchain.certificates.index') }}" class="text-sm text-on-surface-variant opacity-70 hover:text-on-surface-variant">Clear</a>
</form>

{{-- Table --}}
<div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-surface-container-low border-b border-outline-variant/10">
            <tr>
                <th class="text-left px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase">Certificate ID</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase">Student</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase">Event</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase">Achievement</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase">Blockchain</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase">Status</th>
                <th class="text-left px-5 py-3 text-xs font-semibold text-on-surface-variant uppercase">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($certificates as $cert)
            <tr class="hover:bg-surface-container-low transition">
                <td class="px-5 py-3">
                    <p class="font-mono text-xs text-primary">{{ $cert->certificate_id }}</p>
                    <p class="text-xs text-on-surface-variant opacity-70">{{ $cert->issued_date?->format('d M Y') }}</p>
                </td>
                <td class="px-5 py-3">
                    <p class="font-medium text-on-surface font-bold">{{ $cert->student_name }}</p>
                    <p class="text-xs text-on-surface-variant opacity-70">{{ $cert->enrollment_number }}</p>
                </td>
                <td class="px-5 py-3">
                    <p class="text-on-surface">{{ Str::limit($cert->event->name ?? '—', 30) }}</p>
                </td>
                <td class="px-5 py-3 text-on-surface-variant">{{ $cert->achievement }}</td>
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
                    @if($cert->email_sent) <span class="text-xs text-on-surface-variant opacity-70 ml-1">📧</span> @endif
                </td>
                <td class="px-5 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('teacher.certchain.certificates.show', $cert) }}" class="text-xs text-primary hover:underline">View</a>
                        <a href="{{ route('teacher.certchain.certificates.download', $cert) }}" class="text-xs text-on-surface-variant hover:underline">PDF</a>
                    </div>
                </td>
            </tr>
            @empty
            <tr><td colspan="7" class="px-5 py-10 text-center text-on-surface-variant opacity-70">
                No certificates found.
                <a href="{{ route('teacher.certchain.certificates.create') }}" class="text-primary hover:underline ml-1">Issue one →</a>
            </td></tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-5 py-3 border-t border-outline-variant/10">
        {{ $certificates->links() }}
    </div>
</div>
@endsection
