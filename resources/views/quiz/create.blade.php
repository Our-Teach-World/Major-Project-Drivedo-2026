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
                    <input type="text" name="subject" placeholder="e.g. Computer Science" 
                        class="input-field w-full font-bold">
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
@endsection
