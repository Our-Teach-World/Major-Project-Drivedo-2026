@extends('teacher.layout')
@section('title', 'Issue Certificate')
@section('page-title', 'Issue Certificate')
@section('page-subtitle', 'Fill in student details — certificate will be auto-generated & blockchain-recorded')

@section('content')
    <form method="POST" action="{{ route('teacher.certchain.certificates.store') }}" id="issueForm">
        @csrf
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- Left: Form --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Event & Template --}}
                <div class="card p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2"><span>📅</span> Event Details</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Event <span
                                    class="text-red-500">*</span></label>
                            <select name="event_id" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select an event…</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }} ({{ $event->event_date?->format('d M Y') }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Certificate Template <span
                                    class="text-red-500">*</span></label>
                            <select name="template_id" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select a template…</option>
                                @foreach($templates as $template)
                                    <option value="{{ $template->id }}" {{ old('template_id') == $template->id ? 'selected' : '' }}>
                                        {{ $template->name }} ({{ ucfirst($template->type) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Student Info --}}
                <div class="card p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2"><span>🎓</span> Student Information
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Full Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="student_name" value="{{ old('student_name') }}" required
                                placeholder="e.g. Rahul Sharma"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Enrollment Number <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="enrollment_number" value="{{ old('enrollment_number') }}" required
                                placeholder="e.g. 0801CS211001"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address <span
                                    class="text-red-500">*</span></label>
                            <input type="email" name="student_email" value="{{ old('student_email') }}" required
                                placeholder="student@college.edu"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Branch / Department</label>
                            <input type="text" name="student_branch" value="{{ old('student_branch') }}"
                                placeholder="e.g. Computer Science"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Year</label>
                            <select name="student_year"
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <option value="">Select year…</option>
                                @foreach(['1st Year', '2nd Year', '3rd Year', '4th Year'] as $y)
                                    <option {{ old('student_year') === $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Issued Date <span
                                    class="text-red-500">*</span></label>
                            <input type="date" name="issued_date" value="{{ old('issued_date', date('Y-m-d')) }}" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>
                    </div>
                </div>

                {{-- Achievement --}}
                <div class="card p-6">
                    <h3 class="font-semibold text-gray-800 mb-4 flex items-center gap-2"><span>🏆</span> Achievement Details
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Achievement <span
                                    class="text-red-500">*</span></label>
                            <select name="achievement" required
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                @foreach(['Participation', '1st Prize', '2nd Prize', '3rd Prize', 'Best Project', 'Special Award', 'Course Completion', 'Volunteer'] as $ach)
                                    <option {{ old('achievement') === $ach ? 'selected' : '' }}>{{ $ach }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 mb-1.5">Description (optional)</label>
                            <textarea name="description" rows="2"
                                placeholder="e.g. for successfully completing the 2-day workshop on Machine Learning..."
                                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Right: Summary & Options --}}
            <div class="space-y-5">
                <div class="card p-5">
                    <h3 class="font-semibold text-gray-800 mb-3 flex items-center gap-2"><span>⛓</span> Blockchain Process
                    </h3>
                    <div class="space-y-3 text-sm">
                        @foreach(['Certificate data recorded', 'SHA-256 hash computed', 'Block added to chain', 'QR code generated', 'PDF certificate created'] as $step)
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-6 h-6 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-xs font-bold">
                                    {{ $loop->iteration }}</div>
                                <span class="text-gray-600">{{ $step }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card p-5">
                    <h3 class="font-semibold text-gray-800 mb-3">Options</h3>
                    <label
                        class="flex items-center gap-3 p-3 bg-blue-50 rounded-lg cursor-pointer hover:bg-blue-100 transition">
                        <input type="checkbox" name="send_email" value="1" checked
                            class="rounded border-gray-300 text-blue-600">
                        <div>
                            <p class="text-sm font-medium text-blue-900">Send email to student</p>
                            <p class="text-xs text-blue-600">PDF certificate will be attached</p>
                        </div>
                    </label>
                </div>

                <button type="submit"
                    class="w-full py-3 bg-gradient-to-r from-blue-900 to-blue-700 text-white rounded-xl font-semibold text-sm hover:from-blue-800 hover:to-blue-600 transition-all shadow-lg shadow-blue-900/20">
                    ⛓ Issue & Record on Blockchain
                </button>
                <a href="{{ route('teacher.certchain.certificates.index') }}"
                    class="block text-center text-sm text-gray-400 hover:text-gray-600">Cancel</a>
            </div>
        </div>
    </form>
@endsection
