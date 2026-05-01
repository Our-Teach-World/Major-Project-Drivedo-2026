@extends('teacher.layout')

@section('page_title', 'Smart Quiz Management')

@section('content')
<style>
    .workspace-bg { background-color: #CCD0CF; color: #06141B; border-radius: 24px; padding: 48px; }
    .quiz-card {
        background-color: #FFFFFF;
        border-radius: 16px;
        box-shadow: 0px 4px 15px rgba(6, 20, 27, 0.04);
        transition: all 0.2s;
        border: 1px solid rgba(6, 20, 27, 0.02);
    }
    .quiz-card:hover { transform: translateY(-3px); box-shadow: 0px 10px 30px rgba(6, 20, 27, 0.08); }
    .btn-action {
        background-color: #253745;
        color: #CCD0CF;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-action:hover { background-color: #1a2833; }
    .label-caps { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.06em; opacity: 0.6; }
    .status-badge { border-radius: 6px; padding: 4px 10px; font-size: 11px; font-weight: 800; text-transform: uppercase; }
    .status-active { background-color: #253745; color: #CCD0CF; }
    .status-inactive { background-color: rgba(6, 20, 27, 0.1); color: #06141B; }
</style>

<div class="workspace-bg min-h-[80vh]">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-16 gap-6">
        <div>
            <h2 class="text-4xl font-black tracking-tight text-[#06141B]">Assessment Portal</h2>
            <p class="text-lg opacity-60 font-medium">Design and monitor academic evaluations.</p>
        </div>
        <a href="{{ route('teacher.quizzes.create') }}" class="btn-action px-8 py-4 flex items-center gap-3 text-sm shadow-xl active:scale-95">
            <span class="material-symbols-outlined">add_circle</span>
            CREATE NEW SESSION
        </a>
    </div>

    @if(session('success'))
        <div class="bg-white/40 backdrop-blur-md border border-white/20 text-[#06141B] px-6 py-4 rounded-2xl mb-12 font-bold flex items-center gap-3">
            <span class="material-symbols-outlined text-emerald-600">check_circle</span>
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($quizzes as $quiz)
            <div class="quiz-card p-8 flex flex-col">
                <div class="flex justify-between items-start mb-6">
                    <span class="status-badge {{ $quiz->status === 'active' ? 'status-active' : 'status-inactive' }}">
                        {{ $quiz->status }}
                    </span>
                    <span class="label-caps">{{ $quiz->duration_minutes }} Mins</span>
                </div>
                
                <h3 class="text-2xl font-bold text-[#06141B] mb-2">{{ $quiz->title }}</h3>
                <p class="label-caps mb-8">{{ $quiz->subject ?: 'General Curriculum' }}</p>

                <div class="mt-auto pt-6 border-t border-gray-100 space-y-4">
                    <div class="flex justify-between items-center text-xs font-bold opacity-60">
                        <span>QUESTIONS</span>
                        <span>{{ $quiz->questions_count }} ITEMS</span>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <a href="{{ route('teacher.quizzes.questions', $quiz) }}" class="flex items-center justify-center gap-2 bg-[#F2F4F3] py-3 rounded-lg text-[11px] font-black hover:bg-gray-200 transition-colors">
                            <span class="material-symbols-outlined text-sm">edit</span> EDIT
                        </a>
                        <a href="{{ route('teacher.quizzes.results', $quiz) }}" class="flex items-center justify-center gap-2 bg-[#F2F4F3] py-3 rounded-lg text-[11px] font-black hover:bg-gray-200 transition-colors">
                            <span class="material-symbols-outlined text-sm">bar_chart</span> RESULTS
                        </a>
                    </div>
                    
                    <form action="{{ route('teacher.quizzes.toggle', $quiz) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full border-2 border-[#253745]/10 py-3 rounded-lg text-[11px] font-black opacity-60 hover:opacity-100 hover:border-[#253745] transition-all">
                            {{ $quiz->status === 'active' ? 'DEACTIVATE' : 'ACTIVATE' }} SESSION
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="col-span-full py-32 text-center">
                <div class="w-20 h-20 bg-white/40 rounded-full flex items-center justify-center mx-auto mb-6">
                    <span class="material-symbols-outlined text-4xl opacity-30">quiz</span>
                </div>
                <h3 class="text-2xl font-bold opacity-40 uppercase tracking-widest">No Sessions Designed</h3>
                <p class="opacity-30 mt-2 font-medium">Begin by creating your first academic quiz.</p>
            </div>
        @endforelse
    </div>

    <div class="mt-16">
        {{ $quizzes->links() }}
    </div>
</div>
@endsection
