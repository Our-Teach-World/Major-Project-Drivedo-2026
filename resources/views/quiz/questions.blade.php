@extends('teacher.layout')

@section('page_title', 'Manage Questions - ' . $quiz->title)

@section('content')
<style>
    .workspace-bg { background-color: #CCD0CF; color: #06141B; border-radius: 24px; padding: 48px; }
    .question-card {
        background-color: #FFFFFF;
        border-radius: 20px;
        box-shadow: 0px 4px 20px rgba(6, 20, 27, 0.04);
        border: 1px solid rgba(6, 20, 27, 0.02);
        padding: 40px;
        position: relative;
    }
    .input-field {
        background-color: #F8F9F9;
        border: 1px solid rgba(6, 20, 27, 0.08);
        border-radius: 12px;
        padding: 12px 16px;
        transition: all 0.2s;
        font-weight: 600;
        font-size: 14px;
    }
    .input-field:focus {
        border-color: #253745;
        background-color: #FFFFFF;
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
    .btn-add {
        background-color: transparent;
        border: 2px dashed rgba(6, 20, 27, 0.2);
        border-radius: 16px;
        padding: 24px;
        font-weight: 800;
        opacity: 0.6;
        transition: all 0.2s;
    }
    .btn-add:hover { opacity: 1; border-color: #253745; background-color: rgba(255,255,255,0.4); }
    .label-caps { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.4; }
</style>

<div class="workspace-bg min-h-[80vh]">
    <div class="max-w-4xl mx-auto">
        <div class="mb-16 flex flex-col md:flex-row justify-between items-end gap-6">
            <div>
                <a href="{{ route('teacher.quizzes.index') }}" class="inline-flex items-center gap-2 mb-6 text-sm font-bold opacity-60 hover:opacity-100 transition-opacity">
                    <span class="material-symbols-outlined text-sm">arrow_back</span>
                    BACK TO SESSIONS
                </a>
                <h2 class="text-4xl font-black tracking-tight text-[#06141B]">Content Inventory</h2>
                <p class="text-lg opacity-60 font-medium mt-2">Managing assessment items for: <strong class="text-[#253745]">{{ $quiz->title }}</strong></p>
            </div>
            <button type="button" onclick="addQuestion()" class="btn-action px-6 py-3 flex items-center gap-2 text-xs shadow-lg active:scale-95">
                <span class="material-symbols-outlined text-sm">add_circle</span>
                ADD ITEM
            </button>
        </div>

        <form action="{{ route('teacher.quizzes.questions.store', $quiz) }}" method="POST">
            @csrf
            <div id="questionsContainer" class="space-y-10">
                @forelse($questions as $index => $question)
                    <div class="question-card animate-in fade-in slide-in-from-bottom-4" data-index="{{ $index }}">
                        <button type="button" onclick="this.closest('.question-card').remove()" class="absolute top-8 right-8 opacity-20 hover:opacity-100 hover:text-red-600 transition-all">
                            <span class="material-symbols-outlined">delete</span>
                        </button>
                        
                        <div class="grid grid-cols-1 gap-8">
                            <div class="space-y-3">
                                <label class="label-caps">PROMPT / QUESTION TEXT</label>
                                <textarea name="questions[{{ $index }}][question_text]" required class="input-field w-full h-32 resize-none" placeholder="Enter the analytical prompt...">{{ $question->question_text }}</textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="label-caps">OPTION A</label>
                                    <input type="text" name="questions[{{ $index }}][option1]" value="{{ $question->option1 }}" required class="input-field w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="label-caps">OPTION B</label>
                                    <input type="text" name="questions[{{ $index }}][option2]" value="{{ $question->option2 }}" required class="input-field w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="label-caps">OPTION C</label>
                                    <input type="text" name="questions[{{ $index }}][option3]" value="{{ $question->option3 }}" required class="input-field w-full">
                                </div>
                                <div class="space-y-2">
                                    <label class="label-caps">OPTION D</label>
                                    <input type="text" name="questions[{{ $index }}][option4]" value="{{ $question->option4 }}" required class="input-field w-full">
                                </div>
                            </div>

                            <div class="w-full md:w-1/3 space-y-2">
                                <label class="label-caps">VALIDATED KEY (CORRECT OPTION)</label>
                                <select name="questions[{{ $index }}][correct_option]" class="input-field w-full appearance-none">
                                    <option value="1" {{ $question->correct_option == 1 ? 'selected' : '' }}>OPTION A</option>
                                    <option value="2" {{ $question->correct_option == 2 ? 'selected' : '' }}>OPTION B</option>
                                    <option value="3" {{ $question->correct_option == 3 ? 'selected' : '' }}>OPTION C</option>
                                    <option value="4" {{ $question->correct_option == 4 ? 'selected' : '' }}>OPTION D</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="question-card" data-index="0">
                        <div class="grid grid-cols-1 gap-8">
                            <div class="space-y-3">
                                <label class="label-caps">PROMPT / QUESTION TEXT</label>
                                <textarea name="questions[0][question_text]" required class="input-field w-full h-32 resize-none" placeholder="Enter the analytical prompt..."></textarea>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <input type="text" name="questions[0][option1]" required placeholder="Option A" class="input-field w-full">
                                <input type="text" name="questions[0][option2]" required placeholder="Option B" class="input-field w-full">
                                <input type="text" name="questions[0][option3]" required placeholder="Option C" class="input-field w-full">
                                <input type="text" name="questions[0][option4]" required placeholder="Option D" class="input-field w-full">
                            </div>

                            <div class="w-full md:w-1/3 space-y-2">
                                <label class="label-caps">VALIDATED KEY</label>
                                <select name="questions[0][correct_option]" class="input-field w-full appearance-none">
                                    <option value="1">OPTION A</option>
                                    <option value="2">OPTION B</option>
                                    <option value="3">OPTION C</option>
                                    <option value="4">OPTION D</option>
                                </select>
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-12 flex flex-col gap-6">
                <button type="button" onclick="addQuestion()" class="btn-add w-full flex items-center justify-center gap-3">
                    <span class="material-symbols-outlined">add</span>
                    INSERT ADDITIONAL ITEM
                </button>
                
                <button type="submit" class="btn-action w-full py-5 shadow-2xl active:scale-[0.98]">
                    COMMIT ALL CHANGES
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let questionIndex = {{ count($questions) ?: 1 }};

    function addQuestion() {
        const container = document.getElementById('questionsContainer');
        const html = `
            <div class="question-card animate-in fade-in slide-in-from-bottom-4" data-index="${questionIndex}">
                <button type="button" onclick="this.closest('.question-card').remove()" class="absolute top-8 right-8 opacity-20 hover:opacity-100 hover:text-red-600 transition-all">
                    <span class="material-symbols-outlined">delete</span>
                </button>
                
                <div class="grid grid-cols-1 gap-8">
                    <div class="space-y-3">
                        <label class="label-caps">PROMPT / QUESTION TEXT</label>
                        <textarea name="questions[${questionIndex}][question_text]" required class="input-field w-full h-32 resize-none" placeholder="Enter the analytical prompt..."></textarea>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <input type="text" name="questions[${questionIndex}][option1]" required placeholder="Option A" class="input-field w-full">
                        <input type="text" name="questions[${questionIndex}][option2]" required placeholder="Option B" class="input-field w-full">
                        <input type="text" name="questions[${questionIndex}][option3]" required placeholder="Option C" class="input-field w-full">
                        <input type="text" name="questions[${questionIndex}][option4]" required placeholder="Option D" class="input-field w-full">
                    </div>

                    <div class="w-full md:w-1/3 space-y-2">
                        <label class="label-caps">VALIDATED KEY</label>
                        <select name="questions[${questionIndex}][correct_option]" class="input-field w-full appearance-none">
                            <option value="1">OPTION A</option>
                            <option value="2">OPTION B</option>
                            <option value="3">OPTION C</option>
                            <option value="4">OPTION D</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', html);
        questionIndex++;
    }
</script>
@endsection
