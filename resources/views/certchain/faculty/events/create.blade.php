@extends('teacher.layout')
@section('title', 'Create Event')
@section('page-title', 'Create Event')
@section('page-subtitle', 'Add a new college event for certificate issuance')

@section('content')
    <div class="max-w-2xl">
        <form method="POST" action="{{ route('teacher.certchain.events.store') }}">
            @csrf
            <div class="space-y-5">
                <div class="card p-6">
                    <div class="grid md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Event Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                placeholder="e.g. National Tech Symposium 2024"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                            @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Event Type <span
                                    class="text-red-500">*</span></label>
                            <select name="event_type" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('event_type') border-red-500 @enderror">
                                <option value="">Select type…</option>
                                @foreach(['Workshop', 'Seminar', 'Competition', 'Hackathon', 'Symposium', 'Cultural', 'Sports', 'Webinar', 'Conference', 'Training', 'Other'] as $type)
                                    <option value="{{ $type }}" {{ old('event_type') === $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @error('event_type') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Department</label>
                            <input type="text" name="department" value="{{ old('department', $teacherBranch ?? '') }}"
                                placeholder="e.g. Computer Science"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('department') border-red-500 @enderror">
                            @error('department') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Start Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="event_date" value="{{ old('event_date') }}" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('event_date') border-red-500 @enderror">
                            @error('event_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">End Date</label>
                            <input type="date" name="event_end_date" value="{{ old('event_end_date') }}"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('event_end_date') border-red-500 @enderror">
                            @error('event_end_date') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Venue</label>
                            <input type="text" name="venue" value="{{ old('venue') }}"
                                placeholder="e.g. Main Auditorium, Block A"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 @error('venue') border-red-500 @enderror">
                            @error('venue') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                            <textarea name="description" rows="3" placeholder="Brief description of the event…"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="flex gap-3">
                    <button type="submit" class="btn-primary">Create Event</button>
                    <a href="{{ route('teacher.certchain.events.index') }}"
                        class="px-5 py-2 border border-gray-200 rounded-lg text-sm text-gray-600 hover:bg-gray-50">Cancel</a>
                </div>
            </div>
        </form>
    </div>
@endsection