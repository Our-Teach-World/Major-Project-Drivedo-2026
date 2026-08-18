@extends('teacher.layout')

@section('page_title', 'Weekly Attendance Grid')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header Section --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-4xl font-black text-on-surface uppercase tracking-tight">Weekly Grid</h1>
            <p class="text-on-surface-variant font-bold">
                Semester {{ $selectedSem }} — 
                <span class="text-primary">{{ \Carbon\Carbon::parse($dates[0])->format('d M') }}</span> to 
                <span class="text-primary">{{ \Carbon\Carbon::parse($dates[5])->format('d M, Y') }}</span>
            </p>
        </div>
        
        <div class="flex flex-wrap gap-4">
            {{-- Date Selector --}}
            <div class="bg-white p-4 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <form action="{{ route('attendance.bulk') }}" method="GET" class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase text-black">Select Week</label>
                    <div class="flex gap-2">
                        <input type="hidden" name="semester" value="{{ $selectedSem }}">
                        <input type="hidden" name="subject_id" value="{{ $selectedSubject }}">
                        <input type="date" name="date" value="{{ $selectedDate }}" onchange="this.form.submit()" 
                            class="bg-white border-2 border-black text-black font-bold py-2 px-4 focus:ring-0 focus:outline-none cursor-pointer">
                    </div>
                </form>
            </div>

            {{-- Subject Selector --}}
            <div class="bg-white p-4 border-2 border-black shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
                <form action="{{ route('attendance.bulk') }}" method="GET" class="flex flex-col gap-1">
                    <label class="text-[10px] font-black uppercase text-black">Active Subject</label>
                    <div class="flex gap-2">
                        <input type="hidden" name="semester" value="{{ $selectedSem }}">
                        <input type="hidden" name="date" value="{{ $selectedDate }}">
                        <select name="subject_id" onchange="this.form.submit()" class="bg-yellow-400 border-2 border-black text-black font-black py-2 px-4 focus:ring-0 focus:outline-none cursor-pointer">
                            @forelse($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ $selectedSubject == $subject->id ? 'selected' : '' }}>{{ $subject->name }} ({{ $subject->code }})</option>
                            @empty
                                <option value="">No Subjects Found</option>
                            @endforelse
                        </select>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500 text-black border-2 border-black font-bold shadow-[4px_4px_0px_0px_rgba(0,0,0,1)]">
            {{ session('success') }}
        </div>
    @endif

    {{-- Brutalist Table Container --}}
    <div class="bg-white p-1 md:p-6 border-[3px] border-black shadow-[8px_8px_0px_0px_rgba(0,0,0,1)] overflow-hidden">
        <form action="{{ route('attendance.bulk.store') }}" method="POST">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $selectedSubject }}">
            
            <div class="overflow-x-auto">
                <table class="w-full border-collapse">
                    <thead>
                        <tr class="border-b-[3px] border-black">
                            <th class="p-4 text-left border-r-[3px] border-black bg-yellow-400 text-black font-black uppercase text-xs">Enrollment</th>
                            <th class="p-4 text-left border-r-[3px] border-black bg-yellow-400 text-black font-black uppercase text-xs">Student Name</th>
                            <th class="p-4 text-center border-r-[3px] border-black bg-black text-white font-black uppercase text-[10px]">
                                Mark All
                            </th>
                            @foreach($dates as $date)
                                <th class="p-4 text-center border-r-[3px] border-black last:border-r-0 bg-yellow-400 text-black font-black uppercase text-[10px]">
                                    {{ \Carbon\Carbon::parse($date)->format('D') }}<br>
                                    <span>{{ \Carbon\Carbon::parse($date)->format('d/m') }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody class="text-black">
                        @forelse($students as $student)
                            <tr class="border-b-2 border-black hover:bg-gray-50 transition-colors student-row">
                                <td class="p-4 border-r-[3px] border-black font-bold bg-white whitespace-nowrap text-xs">
                                    {{ $student->username }}
                                </td>
                                <td class="p-4 border-r-[3px] border-black font-bold bg-white whitespace-nowrap text-xs">
                                    {{ $student->name ?? $student->username }}
                                </td>
                                <td class="p-4 border-r-[3px] border-black text-center bg-gray-100">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" onchange="toggleRow(this)" 
                                            class="w-6 h-6 border-2 border-black rounded-none appearance-none checked:bg-black checked:border-black transition-all cursor-pointer focus:ring-0">
                                    </label>
                                </td>
                                @foreach($dates as $date)
                                    <td class="p-4 border-r-2 border-black last:border-r-0 text-center bg-white">
                                        @php
                                            $existing = $attendanceData[$student->id][$date][0] ?? null;
                                            $isPresent = $existing ? ($existing->status == 'Present') : true; // Default to checked
                                        @endphp
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="attendance[{{ $student->id }}][{{ $date }}]" value="0">
                                            <input type="checkbox" name="attendance[{{ $student->id }}][{{ $date }}]" value="1" 
                                                class="w-7 h-7 border-2 border-black rounded-none appearance-none checked:bg-primary checked:border-black transition-all cursor-pointer focus:ring-0 peer day-checkbox"
                                                {{ $isPresent ? 'checked' : '' }}>
                                            <span class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 peer-checked:opacity-100">
                                                <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                                            </span>
                                        </label>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-10 text-center font-bold text-gray-500 italic">No students found for Semester {{ $selectedSem }}.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-8 flex justify-end">
                <button type="submit" class="bg-primary text-black border-[3px] border-black px-10 py-4 font-black uppercase tracking-widest text-lg hover:bg-primary-dim hover:-translate-y-1 hover:-translate-x-1 active:translate-y-0 active:translate-x-0 shadow-[6px_6px_0px_0px_rgba(0,0,0,1)] hover:shadow-[10px_10px_0px_0px_rgba(0,0,0,1)] transition-all">
                    Update Weekly Attendance
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleRow(masterCheckbox) {
        const row = masterCheckbox.closest('.student-row');
        const checkboxes = row.querySelectorAll('.day-checkbox');
        checkboxes.forEach(cb => {
            cb.checked = masterCheckbox.checked;
        });
    }
</script>

<style>
    /* Custom Checkbox Style for Brutalist look */
    input[type="checkbox"] {
        background-color: white;
    }
    input[type="checkbox"]:checked {
        background-image: url("data:image/svg+xml,%3csvg viewBox='0 0 16 16' fill='black' xmlns='http://www.w3.org/2000/svg'%3e%3cpath d='M12.207 4.793a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0l-2-2a1 1 0 011.414-1.414L6.5 9.086l4.293-4.293a1 1 0 011.414 0z'/%3e%3c/svg%3e");
        background-size: 100% 100%;
        background-position: center;
        background-repeat: no-repeat;
    }
</style>
@endsection
