@extends('teacher.layout')

@section('page_title', 'Export Monthly Report')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-10 text-center">
        <h1 class="text-4xl font-black text-on-surface uppercase tracking-tighter">Attendance Export</h1>
        <p class="text-on-surface-variant font-medium">Download formal CSV reports for your classes.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-500 text-white border-2 border-black font-bold shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            {{ session('error') }}
        </div>
    @endif

    {{-- Brutalist Form Container --}}
    <div class="bg-white p-8 border-[3px] border-black shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] text-black">
        <form id="exportForm">
            <div class="space-y-6">
                {{-- Semester Selection --}}
                <div>
                    <label class="block text-sm font-black uppercase mb-2">Select Semester</label>
                    <select id="semester" name="semester" required onchange="filterSubjects(this.value)" class="w-full bg-white border-2 border-black p-4 font-bold text-lg focus:ring-0 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] cursor-pointer">
                        <option value="">Choose Semester...</option>
                        @foreach($activeSems as $sem)
                            <option value="{{ $sem }}">Semester {{ $sem }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Subject Selection --}}
                <div>
                    <label class="block text-sm font-black uppercase mb-2">Select Subject</label>
                    <select id="subject" name="subject_id" required class="w-full bg-white border-2 border-black p-4 font-bold text-lg focus:ring-0 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] cursor-pointer">
                        <option value="">Choose Subject...</option>
                    </select>
                </div>

                {{-- Month Selection --}}
                <div>
                    <label class="block text-sm font-black uppercase mb-2">Select Month</label>
                    <select id="month" name="month" required class="w-full bg-white border-2 border-black p-4 font-bold text-lg focus:ring-0 focus:outline-none shadow-[4px_4px_0px_0px_rgba(0,0,0,1)] cursor-pointer">
                        <option value="">Choose Month...</option>
                        @php
                            $months = [
                                1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April',
                                5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August',
                                9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December'
                            ];
                        @endphp
                        @foreach($months as $num => $name)
                            <option value="{{ $num }}" {{ date('n') == $num ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-6">
                    <button type="button" onclick="submitExport()" class="w-full bg-yellow-400 text-black border-[3px] border-black py-4 font-black uppercase tracking-widest text-xl hover:bg-yellow-300 hover:-translate-y-1 hover:-translate-x-1 active:translate-y-0 active:translate-x-0 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] transition-all">
                        Generate CSV Report
                    </button>
                </div>
            </div>
        </form>
    </div>

    <div class="mt-12 p-6 bg-surface-container border-2 border-black/10 rounded-2xl flex items-start gap-4">
        <span class="material-symbols-outlined text-primary text-3xl">info</span>
        <div>
            <h4 class="font-bold text-on-surface">Export Information</h4>
            <p class="text-sm text-on-surface-variant leading-relaxed">
                The generated CSV will include all students registered in the selected semester. Cells will be filled with 'P' for Present, 'A' for Absent, and '-' if no record exists for that date.
            </p>
        </div>
    </div>
</div>

<script>
    // Data from Laravel
    const subjectsBySem = @json($subjectsBySem);

    function filterSubjects(semester) {
        const subjectSelect = document.getElementById('subject');
        subjectSelect.innerHTML = '<option value="">Choose Subject...</option>';
        
        if (semester && subjectsBySem[semester]) {
            subjectsBySem[semester].forEach(subject => {
                const option = document.createElement('option');
                option.value = subject.id;
                option.textContent = `${subject.name} (${subject.code})`;
                subjectSelect.appendChild(option);
            });
        }
    }

    function submitExport() {
        const semester = document.getElementById('semester').value;
        const month = document.getElementById('month').value;
        const subject = document.getElementById('subject').value;
        
        if(!semester || !month || !subject) {
            alert('Please select Semester, Subject and Month.');
            return;
        }

        // Construct the URL: /teacher/attendance/export-download/{semester}/{month}/{subject}
        const url = `/teacher/attendance/export-download/${semester}/${month}/${subject}`;
        window.location.href = url;
    }
</script>
@endsection
