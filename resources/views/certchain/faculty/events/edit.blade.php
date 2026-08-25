@extends('admin.layouts.app')
@section('title','Edit Event')
@section('page-title','Edit Event')

@section('content')
<div class="max-w-2xl">
<form method="POST" action="{{ route('teacher.certchain.events.update', $event) }}">
@csrf @method('PUT')
<div class="space-y-5">
    <div class="card p-6">
        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Event Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $event->name) }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Event Type</label>
                <select name="event_type" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['Workshop','Seminar','Competition','Hackathon','Symposium','Cultural','Sports','Webinar','Conference','Training','Other'] as $type)
                    <option {{ old('event_type', $event->event_type) === $type ? 'selected' : '' }}>{{ $type }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Status</label>
                <select name="status" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                    @foreach(['active','completed','cancelled'] as $s)
                    <option value="{{ $s }}" {{ old('status', $event->status) === $s ? 'selected' : '' }}>{{ ucfirst($s) }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Start Date</label>
                <input type="date" name="event_date" value="{{ old('event_date', $event->event_date?->format('Y-m-d')) }}" required
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">End Date</label>
                <input type="date" name="event_end_date" value="{{ old('event_end_date', $event->event_end_date?->format('Y-m-d')) }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Department</label>
                <input type="text" name="department" value="{{ old('department', $event->department) }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Venue</label>
                <input type="text" name="venue" value="{{ old('venue', $event->venue) }}"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="md:col-span-2">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                <textarea name="description" rows="3"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description', $event->description) }}</textarea>
            </div>
        </div>
    </div>
    <div class="flex gap-3">
        <button type="submit" class="btn-primary">Update Event</button>
        <a href="{{ route('teacher.certchain.events.index') }}" class="px-5 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
    </div>
</div>
</form>
</div>
@endsection
