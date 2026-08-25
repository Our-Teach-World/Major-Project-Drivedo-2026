@extends('admin.layouts.app')
@section('page_title','Issue Certificate')
@section('page-title','Issue Certificate')
@section('page-subtitle','Fill in student details — certificate will be auto-generated & blockchain-recorded')

@section('content')
<form method="POST" action="{{ route('teacher.certchain.certificates.store') }}" id="issueForm">
@csrf
<div class="grid lg:grid-cols-3 gap-6">

    {{-- Left: Form --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Event & Template --}}
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
            <h3 class="font-semibold text-on-surface font-bold mb-4 flex items-center gap-2"><span>📅</span> Event Details</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Event <span class="text-red-500">*</span></label>
                    <select name="event_id" required class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Select an event…</option>
                        @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->name }} ({{ $event->event_date?->format('d M Y') }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Certificate Template <span class="text-red-500">*</span></label>
                    <select name="template_id" required class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
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
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
            <h3 class="font-semibold text-on-surface font-bold mb-4 flex items-center gap-2"><span>🎓</span> Student Information</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="student_name" value="{{ old('student_name') }}" required placeholder="e.g. Rahul Sharma"
                        class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Enrollment Number <span class="text-red-500">*</span></label>
                    <input type="text" name="enrollment_number" id="enrollment_number" value="{{ old('enrollment_number') }}" required placeholder="e.g. 0801CS211001"
                        list="students_list"
                        class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                    <datalist id="students_list">
                        <!-- Populated by JS -->
                    </datalist>
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Email Address <span class="text-red-500">*</span></label>
                    <input type="email" name="student_email" value="{{ old('student_email') }}" required placeholder="student@college.edu"
                        class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Branch / Department</label>
                    <input type="text" name="student_branch" value="{{ old('student_branch') }}" placeholder="e.g. Computer Science"
                        class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Year</label>
                    <select name="student_year" class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Select year…</option>
                        @foreach(['1st Year','2nd Year','3rd Year','4th Year'] as $y)
                        <option {{ old('student_year') === $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Issued Date <span class="text-red-500">*</span></label>
                    <input type="date" name="issued_date" value="{{ old('issued_date', date('Y-m-d')) }}" required
                        class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                </div>
            </div>
        </div>

        {{-- Achievement --}}
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
            <h3 class="font-semibold text-on-surface font-bold mb-4 flex items-center gap-2"><span>🏆</span> Achievement Details</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Achievement <span class="text-red-500">*</span></label>
                    <select name="achievement" required class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        @foreach(['Participation','1st Prize','2nd Prize','3rd Prize','Best Project','Special Award','Course Completion','Volunteer'] as $ach)
                        <option {{ old('achievement') === $ach ? 'selected' : '' }}>{{ $ach }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Description (optional)</label>
                    <textarea name="description" rows="2" placeholder="e.g. for successfully completing the 2-day workshop on Machine Learning..."
                        class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary resize-none">{{ old('description') }}</textarea>
                </div>
            </div>
        </div>
    </div>

    {{-- Right: Summary & Options --}}
    <div class="space-y-5">
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5">
            <h3 class="font-semibold text-on-surface font-bold mb-3 flex items-center gap-2"><span>⛓</span> Blockchain Process</h3>
            <div class="space-y-3 text-sm">
                @foreach(['Certificate data recorded','SHA-256 hash computed','Block added to chain','QR code generated','PDF certificate created'] as $step)
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-primary/10 text-primary flex items-center justify-center text-xs font-bold">{{ $loop->iteration }}</div>
                    <span class="text-on-surface-variant">{{ $step }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5">
            <h3 class="font-semibold text-on-surface font-bold mb-3">Options</h3>
            <label class="flex items-center gap-3 p-3 bg-primary/5 rounded-lg cursor-pointer hover:bg-primary/10 transition">
                <input type="checkbox" name="send_email" value="1" checked class="rounded border-gray-300 text-primary">
                <div>
                    <p class="text-sm font-medium text-blue-900">Send email to student</p>
                    <p class="text-xs text-primary">PDF certificate will be attached</p>
                </div>
            </label>
        </div>

        <button type="submit"
            class="w-full py-3 bg-gradient-to-r from-primary to-primary/80 text-white rounded-xl font-semibold text-sm hover:scale-[1.02] transition-all shadow-lg shadow-primary/20">
            ⛓ Issue & Record on Blockchain
        </button>
        <a href="{{ route('teacher.certchain.certificates.index') }}" class="block text-center text-sm text-on-surface-variant opacity-70 hover:text-on-surface-variant">Cancel</a>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
    const studentsData = {!! $studentsJson ?? '[]' !!};
    const datalist = document.getElementById('students_list');
    
    // Populate datalist
    studentsData.forEach(student => {
        const option = document.createElement('option');
        option.value = student.enrollment_no;
        option.textContent = `${student.name} (${student.branch})`;
        datalist.appendChild(option);
    });

    // Auto-fill logic
    document.getElementById('enrollment_number').addEventListener('input', function(e) {
        const enrollment = e.target.value;
        const student = studentsData.find(s => s.enrollment_no === enrollment);
        if (student) {
            document.querySelector('[name="student_name"]').value = student.name;
            document.querySelector('[name="student_email"]').value = student.email;
            document.querySelector('[name="student_branch"]').value = student.branch;
            
            const yearSelect = document.querySelector('[name="student_year"]');
            if(yearSelect) {
                Array.from(yearSelect.options).forEach(opt => {
                    if(opt.value === student.year) opt.selected = true;
                });
            }
        }
    });
</script>
@endpush
