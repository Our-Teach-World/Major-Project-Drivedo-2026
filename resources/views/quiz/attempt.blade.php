@extends('teacher.layout')

@section('page_title', 'Student Attempt Details')

@section('content')
<style>
    .workspace-bg { background-color: #CCD0CF; color: #06141B; border-radius: 24px; padding: 48px; }
    .card-surface {
        background-color: #FFFFFF;
        border-radius: 20px;
        box-shadow: 0px 4px 20px rgba(6, 20, 27, 0.04);
        border: 1px solid rgba(6, 20, 27, 0.02);
    }
    .label-caps { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.5; }
</style>

<div class="workspace-bg min-h-[80vh]">
    <div class="mb-12">
        <a href="{{ route('teacher.quizzes.results', $quiz) }}" class="inline-flex items-center gap-2 mb-6 text-sm font-bold opacity-60 hover:opacity-100 transition-opacity">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            BACK TO RESULTS
        </a>
        <h2 class="text-4xl font-black tracking-tight text-[#06141B]">Attempt Details</h2>
        <p class="text-lg opacity-60 font-medium mt-2">Candidate: <strong class="text-[#253745]">{{ $result->user->username }}</strong> | Quiz: <strong class="text-[#253745]">{{ $quiz->title }}</strong></p>
    </div>

    <div class="card-surface p-10 mb-8 flex justify-between items-center">
        <div>
            <p class="label-caps mb-1">SCORE</p>
            <p class="text-4xl font-black">{{ $result->score }} / {{ $result->total_questions }}</p>
        </div>
        <div>
            <p class="label-caps mb-1">PERCENTAGE</p>
            <p class="text-4xl font-black {{ $result->percentage >= 70 ? 'text-emerald-500' : ($result->percentage >= 40 ? 'text-amber-500' : 'text-red-500') }}">{{ number_format($result->percentage, 1) }}%</p>
        </div>
        <div>
            <form method="POST" action="{{ route('teacher.quizzes.attempt.reset', ['quiz' => $quiz, 'result' => $result]) }}" onsubmit="return confirm('Reset this attempt? The student will be able to retake the quiz.')">
                @csrf @method('DELETE')
                <button type="submit" class="px-6 py-3 bg-red-100 text-red-700 font-bold rounded-xl text-sm hover:bg-red-200 transition shadow-sm">Reset Attempt</button>
            </form>
        </div>
    </div>

    <h3 class="text-2xl font-black mb-6">Question Breakdown</h3>
    
    <div class="space-y-6">
        @foreach($quiz->questions as $index => $question)
            @php
                $userAnswer = $userAnswers->get($question->id);
                $selectedOption = $userAnswer ? $userAnswer->selected_option : null;
                $isCorrect = $selectedOption == $question->correct_option;
            @endphp
            <div class="card-surface p-8 border-l-8 {{ $isCorrect ? 'border-emerald-500' : 'border-red-500' }}">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 flex-shrink-0 rounded-full flex items-center justify-center font-black text-lg {{ $isCorrect ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-grow">
                        <p class="font-bold text-xl mb-6 text-[#06141B]">{{ $question->question_text }}</p>
                        <div class="grid md:grid-cols-2 gap-4">
                            @for($i = 1; $i <= 4; $i++)
                                @php
                                    $optText = $question->{"option_".$i};
                                    if(!$optText) continue;
                                    
                                    $isThisSelected = ($selectedOption == $i);
                                    $isThisCorrect = ($question->correct_option == $i);
                                    
                                    $optClass = "border-gray-200 bg-gray-50 opacity-60";
                                    if($isThisCorrect) {
                                        $optClass = "border-emerald-500 bg-emerald-50 text-emerald-900 font-bold shadow-sm ring-1 ring-emerald-500";
                                    } elseif($isThisSelected && !$isThisCorrect) {
                                        $optClass = "border-red-500 bg-red-50 text-red-900 shadow-sm ring-1 ring-red-500";
                                    }
                                @endphp
                                <div class="px-5 py-4 border rounded-xl {{ $optClass }}">
                                    <div class="flex items-center justify-between">
                                        <span>{{ $optText }}</span>
                                        @if($isThisCorrect)
                                            <span class="text-emerald-600 font-black tracking-widest text-xs uppercase">Correct</span>
                                        @elseif($isThisSelected)
                                            <span class="text-red-600 font-black tracking-widest text-xs uppercase">Student Answer</span>
                                        @endif
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
