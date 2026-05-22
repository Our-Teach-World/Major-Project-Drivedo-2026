@extends('teacher.layout')
@section('page_title','Events')
@section('page-title','Events')
@section('page-subtitle','Manage college events for certificate issuance')



@section('content')
<div class="flex justify-end mb-4"><a href="{{ route('teacher.certchain.events.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all text-sm">+ Create Event</a></div>
<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-5">
    @forelse($events as $event)
    <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5 flex flex-col">
        <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl bg-blue-900/10 flex items-center justify-center text-xl">
                @php
                $icons = ['Workshop'=>'🔧','Seminar'=>'🎤','Competition'=>'🏆','Hackathon'=>'💻','Symposium'=>'🎓','Cultural'=>'🎭','Sports'=>'⚽'];
                echo $icons[$event->event_type] ?? '📅';
                @endphp
            </div>
            <span class="px-2 py-0.5 rounded-full text-xs font-medium {{ $event->status === 'active' ? 'bg-green-100 text-green-700' : ($event->status === 'completed' ? 'bg-primary/10 text-blue-700' : 'bg-red-100 text-red-600') }}">
                {{ ucfirst($event->status) }}
            </span>
        </div>

        <h3 class="font-semibold text-on-surface font-bold mb-1 leading-tight">{{ $event->name }}</h3>
        <p class="text-xs text-on-surface-variant mb-1">{{ $event->event_type }} &bull; {{ $event->department ?? 'All Departments' }}</p>
        <p class="text-xs text-on-surface-variant opacity-70 mb-3">📅 {{ $event->event_date?->format('d M Y') }}
            @if($event->event_end_date) – {{ $event->event_end_date?->format('d M Y') }} @endif
            @if($event->venue) &bull; 📍 {{ $event->venue }} @endif
        </p>

        @if($event->description)
        <p class="text-xs text-on-surface-variant mb-3 line-clamp-2">{{ $event->description }}</p>
        @endif

        <div class="mt-auto pt-3 border-t border-outline-variant/10 flex items-center justify-between">
            <span class="text-xs text-on-surface-variant">
                📜 {{ $event->certificates->count() }} certificate{{ $event->certificates->count() !== 1 ? 's' : '' }}
            </span>
            <div class="flex gap-2">
                <a href="{{ route('teacher.certchain.certificates.create') }}?event_id={{ $event->id }}" class="text-xs text-primary hover:underline">Issue</a>
                <a href="{{ route('teacher.certchain.events.edit', $event) }}" class="text-xs text-on-surface-variant hover:underline">Edit</a>
                @if($event->certificates->count() === 0)
                <form method="POST" action="{{ route('teacher.certchain.events.destroy', $event) }}" onsubmit="return confirm('Delete this event?')">
                    @csrf @method('DELETE')
                    <button class="text-xs text-red-400 hover:underline">Delete</button>
                </form>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="md:col-span-3 card p-12 text-center">
        <p class="text-4xl mb-3">📅</p>
        <p class="text-on-surface-variant font-medium">No events yet</p>
        <p class="text-on-surface-variant opacity-70 text-sm mt-1 mb-4">Create your first event to start issuing certificates</p>
        <a href="{{ route('teacher.certchain.events.create') }}" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all inline-block text-sm">+ Create Event</a>
    </div>
    @endforelse
</div>

<div class="mt-5">{{ $events->links() }}</div>
@endsection
