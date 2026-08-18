@extends('admin.layouts.app')
@section('page_title','Bulk Issue Certificates')
@section('page-title','Bulk Issue Certificates')
@section('page-subtitle','Issue certificates to multiple students at once')

@section('content')
<div class="grid lg:grid-cols-3 gap-6">
<form method="POST" action="{{ route('teacher.certchain.certificates.bulkStore') }}" id="bulkForm" class="lg:col-span-2">
@csrf
    <div class="space-y-5">
        {{-- Event & Template --}}
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
            <h3 class="font-semibold text-on-surface font-bold mb-4">📅 Event & Template</h3>
            <div class="grid md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Event <span class="text-red-500">*</span></label>
                    <select name="event_id" required class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Select an event…</option>
                        @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->name }} ({{ $event->event_date?->format('d M Y') }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Template <span class="text-red-500">*</span></label>
                    <select name="template_id" required class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        <option value="">Select a template…</option>
                        @foreach($templates as $t)
                        <option value="{{ $t->id }}">{{ $t->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        {{-- Students Table --}}
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-semibold text-on-surface font-bold">🎓 Students List</h3>
                <button type="button" onclick="addRow()" class="text-sm text-primary hover:underline">+ Add Row</button>
            </div>
            <div class="overflow-x-auto">
                <datalist id="bulk_students_list">
                    <!-- Populated by JS -->
                </datalist>
                <table class="w-full text-sm" id="studentsTable">
                    <thead class="bg-surface-container-low">
                        <tr>
                            <th class="text-left px-3 py-2 text-xs text-on-surface-variant font-semibold">Full Name *</th>
                            <th class="text-left px-3 py-2 text-xs text-on-surface-variant font-semibold">Enrollment No *</th>
                            <th class="text-left px-3 py-2 text-xs text-on-surface-variant font-semibold">Email *</th>
                            <th class="text-left px-3 py-2 text-xs text-on-surface-variant font-semibold">Branch</th>
                            <th class="text-left px-3 py-2 text-xs text-on-surface-variant font-semibold">Year</th>
                            <th class="text-left px-3 py-2 text-xs text-on-surface-variant font-semibold">Achievement</th>
                            <th class="px-3 py-2"></th>
                        </tr>
                    </thead>
                    <tbody id="studentsBody">
                        @for($i = 0; $i < 3; $i++)
                        <tr class="border-t border-outline-variant/10">
                            <td class="px-2 py-1.5"><input type="text" name="students[{{ $i }}][student_name]" required class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400" placeholder="Student name"></td>
                            <td class="px-2 py-1.5"><input type="text" name="students[{{ $i }}][enrollment_number]" required class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400 enrollment-input" list="bulk_students_list" placeholder="0801CS21XXXX"></td>
                            <td class="px-2 py-1.5"><input type="email" name="students[{{ $i }}][student_email]" required class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400" placeholder="email@college.edu"></td>
                            <td class="px-2 py-1.5"><input type="text" name="students[{{ $i }}][student_branch]" class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400" placeholder="CSE"></td>
                            <td class="px-2 py-1.5"><input type="text" name="students[{{ $i }}][student_year]" class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400" placeholder="3rd Year"></td>
                            <td class="px-2 py-1.5">
                                <select name="students[{{ $i }}][achievement]" class="border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none">
                                    @foreach(['Participation','1st Prize','2nd Prize','3rd Prize','Best Project','Special Award'] as $a)
                                    <option>{{ $a }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="px-2 py-1.5"><button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 text-xs">✕</button></td>
                        </tr>
                        @endfor
                    </tbody>
                </table>
            </div>
            <p class="text-xs text-on-surface-variant opacity-70 mt-2" id="rowCount">3 students</p>
        </div>

        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5 flex items-center gap-4">
            <label class="flex items-center gap-3 cursor-pointer">
                <input type="checkbox" name="send_emails" value="1" checked class="rounded border-gray-300 text-primary">
                <div>
                    <p class="text-sm font-medium text-on-surface">Send emails to all students</p>
                    <p class="text-xs text-on-surface-variant opacity-70">Each student will receive their certificate PDF via email</p>
                </div>
            </label>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="flex-1 py-3 bg-gradient-to-r from-primary to-primary/80 text-white rounded-xl font-semibold text-sm hover:from-blue-800 transition-all shadow-lg shadow-primary/20">
                ⛓ Issue All Certificates on Blockchain
            </button>
            <a href="{{ route('teacher.certchain.certificates.index') }}" class="px-6 py-3 border border-outline-variant/20 rounded-xl text-sm text-on-surface-variant hover:bg-surface-container-low">Cancel</a>
        </div>
    </div>
</form>

{{-- Side Tips --}}
<div class="space-y-4">
    <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5">
        <h4 class="font-semibold text-on-surface font-bold mb-3">💡 Tips</h4>
        <ul class="text-xs text-on-surface-variant space-y-2">
            <li>• Each student gets a unique Certificate ID</li>
            <li>• Enrollment number must be unique per event</li>
            <li>• All certificates are individually blockchain-recorded</li>
            <li>• PDFs are auto-generated from the selected template</li>
            <li>• Emails are sent in the background</li>
        </ul>
    </div>
    <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5">
        <h4 class="font-semibold text-on-surface font-bold mb-2">Issued Date</h4>
        <input type="date" name="issued_date" form="bulkForm" value="{{ date('Y-m-d') }}"
            class="w-full border border-outline-variant/20 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
        <p class="text-xs text-on-surface-variant opacity-70 mt-1">Applied to all certificates in this batch</p>
    </div>
</div>
</div>

@push('scripts')
<script>
let rowIdx = 3;
function addRow() {
    const tbody = document.getElementById('studentsBody');
    const i = rowIdx++;
    const tr = document.createElement('tr');
    tr.className = 'border-t border-outline-variant/10';
    tr.innerHTML = `
        <td class="px-2 py-1.5"><input type="text" name="students[${i}][student_name]" required class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400" placeholder="Student name"></td>
        <td class="px-2 py-1.5"><input type="text" name="students[${i}][enrollment_number]" required class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400 enrollment-input" list="bulk_students_list" placeholder="0801CS21XXXX"></td>
        <td class="px-2 py-1.5"><input type="email" name="students[${i}][student_email]" required class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400" placeholder="email@college.edu"></td>
        <td class="px-2 py-1.5"><input type="text" name="students[${i}][student_branch]" class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400" placeholder="CSE"></td>
        <td class="px-2 py-1.5"><input type="text" name="students[${i}][student_year]" class="w-full border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none focus:ring-1 focus:ring-blue-400" placeholder="3rd Year"></td>
        <td class="px-2 py-1.5"><select name="students[${i}][achievement]" class="border border-outline-variant/20 rounded px-2 py-1 text-xs focus:outline-none">
            <option>Participation</option><option>1st Prize</option><option>2nd Prize</option><option>3rd Prize</option><option>Best Project</option>
        </select></td>
        <td class="px-2 py-1.5"><button type="button" onclick="removeRow(this)" class="text-red-400 hover:text-red-600 text-xs">✕</button></td>`;
    tbody.appendChild(tr);
    updateCount();
}
function removeRow(btn) {
    const rows = document.getElementById('studentsBody').querySelectorAll('tr');
    if (rows.length <= 1) return;
    btn.closest('tr').remove();
    updateCount();
}
function updateCount() {
    const n = document.getElementById('studentsBody').querySelectorAll('tr').length;
    document.getElementById('rowCount').textContent = n + ' student' + (n !== 1 ? 's' : '');
}

const studentsData = {!! $studentsJson ?? '[]' !!};
const datalist = document.getElementById('bulk_students_list');

// Populate datalist
studentsData.forEach(student => {
    const option = document.createElement('option');
    option.value = student.enrollment_no;
    option.textContent = `${student.name} (${student.branch})`;
    datalist.appendChild(option);
});

// Event delegation for dynamically added rows
document.getElementById('studentsBody').addEventListener('input', function(e) {
    if(e.target.classList.contains('enrollment-input')) {
        const enrollment = e.target.value;
        const student = studentsData.find(s => s.enrollment_no === enrollment);
        if(student) {
            const tr = e.target.closest('tr');
            tr.querySelector('input[name*="[student_name]"]').value = student.name;
            tr.querySelector('input[name*="[student_email]"]').value = student.email;
            tr.querySelector('input[name*="[student_branch]"]').value = student.branch;
            tr.querySelector('input[name*="[student_year]"]').value = student.year;
        }
    }
});
</script>
@endpush
@endsection
