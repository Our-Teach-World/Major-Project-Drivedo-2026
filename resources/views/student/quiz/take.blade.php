<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $quiz->title }} - CampusCore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #CCD0CF; 
            color: #06141B;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .header-glass {
            background-color: rgba(204, 208, 207, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(6, 20, 27, 0.05);
        }
        .quiz-panel {
            background-color: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0px 4px 20px rgba(6, 20, 27, 0.04);
            border: 1px solid rgba(6, 20, 27, 0.02);
        }
        .option-card {
            background-color: #F8F9F9;
            border: 1px solid rgba(6, 20, 27, 0.08);
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        .option-input:checked + .option-card {
            background-color: #253745;
            color: #CCD0CF;
            border-color: #253745;
            box-shadow: 0px 8px 16px rgba(37, 55, 69, 0.2);
            transform: scale(1.02);
        }
        .option-card:hover:not(.checked) {
            border-color: #253745;
            background-color: #FFFFFF;
        }
        .btn-submit {
            background-color: #253745;
            color: #CCD0CF;
            border-radius: 12px;
            box-shadow: 0px 10px 25px rgba(37, 55, 69, 0.15);
            transition: all 0.3s;
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0px 15px 35px rgba(37, 55, 69, 0.25);
        }
        #timer.urgent {
            color: #C53030;
            animation: pulse 1s infinite;
        }
        @keyframes pulse {
            0% { opacity: 1; }
            50% { opacity: 0.5; }
            100% { opacity: 1; }
        }
        .label-caps {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            opacity: 0.6;
        }
    </style>
</head>
<body class="min-h-screen pb-32">
    <!-- Sticky Header -->
    <header class="header-glass sticky top-0 z-50 py-6 px-6">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <div>
                <span class="label-caps mb-1 block">Active Assessment</span>
                <h1 class="text-xl font-extrabold tracking-tight">{{ $quiz->title }}</h1>
            </div>
            <div class="text-right">
                <span class="label-caps mb-1 block">Time Remaining</span>
                <div id="timer" class="text-3xl font-black tabular-nums tracking-tighter">00:00</div>
            </div>
        </div>
    </header>

    <div class="max-w-3xl mx-auto mt-12 px-6">
        <form id="quizForm" class="space-y-10">
            @csrf
            @foreach($questions as $i => $q)
                <div class="quiz-panel p-10 animate-in fade-in slide-in-from-bottom-6" style="animation-delay: {{ $i * 100 }}ms">
                    <div class="flex items-start gap-5 mb-8">
                        <div class="bg-[#253745] text-white w-10 h-10 flex items-center justify-center rounded-xl text-sm font-black shadow-lg shrink-0">
                            {{ $i + 1 }}
                        </div>
                        <h3 class="text-2xl font-bold leading-snug pt-1 text-[#06141B]">
                            {{ $q->question_text }}
                        </h3>
                    </div>
                    
                    <div class="grid grid-cols-1 gap-4">
                        @foreach([1, 2, 3, 4] as $optNum)
                            <label class="block relative">
                                <input type="radio" name="answers[{{ $q->id }}]" value="{{ $optNum }}" class="option-input hidden">
                                <div class="option-card px-6 py-5 font-bold flex items-center gap-5">
                                    <span class="w-7 h-7 rounded-lg border-2 border-current/20 flex items-center justify-center text-[11px] opacity-40">
                                        {{ chr(64 + $optNum) }}
                                    </span>
                                    {{ $q->{'option'.$optNum} }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach

            <div class="pt-12 text-center">
                <button type="submit" id="submitBtn" class="btn-submit px-16 py-5 font-black text-lg uppercase tracking-widest transition-all active:scale-95">
                    Submit Assessment
                </button>
                <p class="mt-6 text-sm font-bold opacity-40 uppercase tracking-widest">Ensure all questions are answered</p>
            </div>
        </form>
    </div>

    <script>
        let duration = {{ $quiz->duration_minutes * 60 }};
        const timerDisplay = document.getElementById('timer');
        const quizForm = document.getElementById('quizForm');
        
        const countdown = setInterval(() => {
            const minutes = Math.floor(duration / 60);
            const seconds = duration % 60;
            
            timerDisplay.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (duration <= 60) {
                timerDisplay.classList.add('urgent');
            }

            if (duration <= 0) {
                clearInterval(countdown);
                autoSubmit();
            }
            duration--;
        }, 1000);

        function autoSubmit() {
            submitQuiz();
        }

        quizForm.onsubmit = (e) => {
            e.preventDefault();
            if(confirm('Finalize your responses and submit?')) {
                submitQuiz();
            }
        };

        function submitQuiz() {
            const formData = new FormData(quizForm);
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.disabled = true;
            submitBtn.textContent = 'SUBMITTING SECURELY...';

            fetch('{{ route("student.quizzes.submit", $quiz) }}', {
                method: 'POST',
                body: formData,
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            })
            .then(res => res.json())
            .then(data => {
                if(data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert('Submission Error: ' + data.message);
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'SUBMIT ASSESSMENT';
                }
            })
            .catch(err => {
                console.error(err);
                submitBtn.disabled = false;
                submitBtn.textContent = 'SUBMIT ASSESSMENT';
            });
        }
    </script>
</body>
</html>