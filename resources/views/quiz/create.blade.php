@extends('teacher.layout')

@section('page_title', 'Create New Quiz')

@section('content')
<style>
    .workspace-bg { background-color: #CCD0CF; color: #06141B; border-radius: 24px; padding: 48px; }
    .form-card {
        background-color: #FFFFFF;
        border-radius: 20px;
        box-shadow: 0px 4px 25px rgba(6, 20, 27, 0.04);
        border: 1px solid rgba(6, 20, 27, 0.02);
    }
    .input-field {
        background-color: #F8F9F9;
        border: 1px solid rgba(6, 20, 27, 0.1);
        border-radius: 12px;
        padding: 16px;
        transition: all 0.2s;
    }
    .input-field:focus {
        border-color: #253745;
        background-color: #FFFFFF;
        box-shadow: 0 0 0 4px rgba(37, 55, 69, 0.05);
        outline: none;
    }
    .btn-action {
        background-color: #253745;
        color: #CCD0CF;
        border-radius: 12px;
        font-weight: 800;
        letter-spacing: 0.05em;
        transition: all 0.2s;
    }
    .btn-action:hover { opacity: 0.9; transform: translateY(-2px); }
</style>

<div class="workspace-bg min-h-[80vh]">
    <div class="max-w-2xl mx-auto">
        <div class="mb-12">
            <a href="{{ route('teacher.quizzes.index') }}" class="inline-flex items-center gap-2 mb-6 text-sm font-bold opacity-60 hover:opacity-100 transition-opacity">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                BACK TO SESSIONS
            </a>
            <h2 class="text-4xl font-black tracking-tight text-[#06141B]">Session Definition</h2>
            <p class="text-lg opacity-60 font-medium mt-2">Initialize the core parameters of your assessment.</p>
        </div>

        <form action="{{ route('teacher.quizzes.store') }}" method="POST" class="form-card p-12 space-y-10">
            @csrf
            
            <div class="space-y-3">
                <label class="text-[11px] font-black uppercase tracking-widest opacity-50">Assessment Title</label>
                <input type="text" name="title" required placeholder="e.g. Advanced Cryptography Final" 
                    class="input-field w-full text-lg font-bold">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="space-y-3">
                    <label class="text-[11px] font-black uppercase tracking-widest opacity-50">Academic Subject</label>
                    <select name="subject" id="subject_select" onchange="toggleCustomSubjectInput(this.value)" class="input-field w-full font-bold text-navy bg-[#F8F9F9] focus:bg-white">
                        <option value="">Select a Subject</option>
                        
                        @if($subjects->count() > 0)
                            <optgroup label="My Assigned Subjects">
                                @foreach($subjects as $sub)
                                    <option value="{{ $sub->name }}">{{ $sub->name }} ({{ $sub->code }})</option>
                                @endforeach
                            </optgroup>
                        @endif

                        @if($allSubjects->count() > 0)
                            <optgroup label="All System Subjects">
                                @foreach($allSubjects as $sub)
                                    @if(!$subjects->contains('id', $sub->id))
                                        <option value="{{ $sub->name }}">{{ $sub->name }} ({{ $sub->code }})</option>
                                    @endif
                                @endforeach
                            </optgroup>
                        @endif
                        
                        <option value="custom" class="text-indigo-600 font-bold">Type Custom Subject...</option>
                    </select>

                    <!-- Hidden Custom Subject Text Input -->
                    <div id="custom_subject_container" class="hidden mt-3 animate-in fade-in duration-200">
                        <label class="text-[10px] font-bold uppercase tracking-wider text-indigo-600">Enter Custom Subject Name</label>
                        <input type="text" name="custom_subject" id="custom_subject_input" placeholder="e.g. Artificial Intelligence" 
                            class="input-field w-full font-bold mt-1 border-indigo-200 focus:border-indigo-500">
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[11px] font-black uppercase tracking-widest opacity-50">Time Limit (Minutes)</label>
                    <input type="number" name="duration_minutes" required min="1" max="120" value="5"
                        class="input-field w-full font-bold">
                </div>
            </div>

            <div class="pt-8">
                <button type="submit" class="btn-action w-full py-5 flex items-center justify-center gap-4 shadow-xl active:scale-95">
                    INITIALIZE & ADD QUESTIONS
                    <span class="material-symbols-outlined">arrow_forward</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleCustomSubjectInput(val) {
        const container = document.getElementById('custom_subject_container');
        const input = document.getElementById('custom_subject_input');
        if (val === 'custom') {
            container.classList.remove('hidden');
            input.required = true;
            input.focus();
        } else {
            container.classList.add('hidden');
            input.required = false;
        }
    }
</script>
@endsection
