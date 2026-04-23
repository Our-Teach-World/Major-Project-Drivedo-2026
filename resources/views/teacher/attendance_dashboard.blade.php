@extends('teacher.layout')

@section('page_title', 'Smart Attendance')

@section('content')

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3 text-emerald-400">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-xl flex items-start gap-3 text-error">
            <span class="material-symbols-outlined text-lg mt-0.5">error</span>
            <div class="text-sm space-y-0.5">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <header class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-on-surface tracking-tight mb-2">Smart Attendance</h1>
            <p class="text-on-surface-variant">Manage and take attendance for your active semesters.</p>
        </div>
        <a href="{{ route('attendance.export.view') }}" class="bg-primary text-on-primary-fixed px-6 py-3 rounded-2xl font-bold flex items-center gap-2 hover:scale-105 transition-all shadow-[0_10px_20px_rgba(179,161,255,0.3)]">
            <span class="material-symbols-outlined">download</span>
            Export Monthly Report
        </a>
    </header>

    {{-- ── Assigned Classes (Smart Attendance) ── --}}
    <section id="smart-attendance" class="mb-10">
        <div class="flex items-center justify-between mb-4 px-1">
            <h2 class="text-xl font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-primary">fact_check</span>
                Take Smart Attendance
            </h2>
        </div>
        
        @php 
            $activeSems = json_decode(optional($teacherProfile ?? null)->semester ?? '[]', true) ?? [];
            if(!is_array($activeSems) && !empty($teacherProfile->semester)) {
                $activeSems = [$teacherProfile->semester];
            }
        @endphp

        @if(empty($activeSems))
            <div class="p-6 bg-surface-container/30 border border-dashed border-outline-variant/20 rounded-2xl text-center">
                <span class="material-symbols-outlined text-4xl text-amber-400 mb-2">warning</span>
                <p class="text-on-surface-variant text-sm mb-3">No active classes assigned yet.</p>
                <button onclick="openDrawer()" class="text-sm font-bold text-primary hover:text-primary-dim transition-colors">
                    + Set Semesters in Profile
                </button>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($activeSems as $sem)
                    <div class="bg-surface-container rounded-2xl p-5 border border-outline-variant/10 hover:border-primary/30 transition-all hover:shadow-[0_8px_30px_rgba(108,59,255,0.1)] group">
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-12 h-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-2xl group-hover:scale-110 transition-transform">group</span>
                            </div>
                            <span class="bg-surface-container-high text-xs font-bold px-3 py-1 rounded-full text-on-surface-variant">
                                Sem {{ $sem }}
                            </span>
                        </div>
                        <h3 class="text-lg font-bold text-on-surface mb-1">Semester {{ $sem }} Students</h3>
                        <p class="text-xs text-on-surface-variant mb-5">Take today's attendance</p>
                        
                        <div class="flex flex-col gap-2">
                            <a href="{{ route('attendance.create', ['semester' => $sem]) }}" 
                               class="w-full bg-primary/20 hover:bg-primary text-primary hover:text-on-primary-fixed font-bold text-sm py-2.5 rounded-xl flex items-center justify-center gap-2 transition-all">
                                <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                                Mark Today
                            </a>
                            
                            <a href="{{ route('attendance.bulk', ['semester' => $sem]) }}" 
                               class="w-full bg-surface-bright hover:bg-primary/20 text-on-surface-variant hover:text-primary font-bold text-sm py-2.5 rounded-xl flex items-center justify-center gap-2 transition-all border border-outline-variant/10">
                                <span class="material-symbols-outlined text-[18px]">grid_on</span>
                                Submit Weekly Attendance
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

@endsection
