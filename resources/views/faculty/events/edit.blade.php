@extends('teacher.layout')
@section('page_title','Edit Event')
@section('page-title','Edit Event')

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('teacher.certchain.events.update', $event) }}">
@csrf @method('PUT')
<div class="space-y-5">
    <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-on-surface mb-1.5">Event Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $event->name) }}" required
                    class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-on-surface mb-1.5">Event Type</label>
                <select name="event_type" required class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    @foreach(['Workshop','Seminar','Competition','Hackathon','Symposium','Cultural','Sports','Webinar','Conference','Training','Other'] as $type)
                    <option {{ old('event_type', $event->event_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-on-surface mb-1.5">Status</label>
                <select name="status" class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    @foreach(['active','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $event->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-on-surface mb-1.5">Start Date</label>
                <input type="date" name="event_date" value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}" required
                    class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-on-surface mb-1.5">End Date</label>
                <input type="date" name="event_end_date" value="{{ old('event_end_date', $event->event_end_date?->format('Y-m-d')) }}"
                    class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-on-surface mb-1.5">Department</label>
                <input type="text" name="department" value="{{ old('department', $event->department) }}"
                    class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div>
                <label class="block text-sm font-medium text-on-surface mb-1.5">Venue</label>
                <input type="text" name="venue" value="{{ old('venue', $event->venue) }}"
                    class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-on-surface mb-1.5">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary resize-none">{{ old('description', $event->description) }}</textarea>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all">Update Event</button>
        <a href="{{ route('teacher.certchain.events.index') }}" class="px-5 py-2 border border-outline-variant/20 rounded-lg text-sm text-on-surface-variant hover:bg-surface-container-low">Cancel</a>
    </div>
</div>
</form>
</div>
@endsection
