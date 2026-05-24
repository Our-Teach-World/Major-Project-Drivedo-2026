<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Result - CampusCore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #CCD0CF; 
            color: #06141B;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .card-surface {
            background-color: #FFFFFF;
            border-radius: 24px;
            box-shadow: 0px 4px 30px rgba(6, 20, 27, 0.05);
            border: 1px solid rgba(6, 20, 27, 0.02);
        }
        .score-display {
            background-color: #253745;
            color: #CCD0CF;
            border-radius: 20px;
            box-shadow: 0px 15px 40px rgba(37, 55, 69, 0.2);
        }
        .label-caps {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            opacity: 0.6;
        }
        .btn-action {
            background-color: #253745;
            color: #CCD0CF;
            border-radius: 12px;
            transition: all 0.2s;
        }
        .btn-action:hover {
            opacity: 0.9;
            transform: translateY(-2px);
        }
        .metric-card {
            background-color: #F8F9F9;
            border-radius: 16px;
            border: 1px solid rgba(6, 20, 27, 0.05);
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
            animation: fadeIn 0.25s ease-out forwards;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="min-h-screen px-6 py-16 md:px-20">
    <div class="max-w-5xl mx-auto">
        <!-- Header -->
        <header class="mb-12 text-center md:text-left">
            <a href="{{ route('student.quizzes.index') }}" class="inline-flex items-center gap-2 mb-6 text-sm font-bold opacity-60 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Sessions Overview
            </a>
            <h1 class="text-4xl font-extrabold tracking-tight text-[#06141B]">Performance Analytics</h1>
            <p class="text-lg opacity-70 mt-2 font-semibold text-navy">{{ $quiz->title }}</p>
        </header>

        <!-- Navigation Tabs Bar -->
        <div class="flex border-b border-[#253745]/15 mb-10 gap-2 overflow-x-auto whitespace-nowrap">
            <button onclick="switchTab('overview-tab')" id="btn-overview-tab" class="tab-btn active border-b-2 border-[#253745] px-6 py-4 text-sm font-bold text-navy transition-all focus:outline-none flex items-center gap-2">
                <span>📊</span> Overview & Benchmarks
            </button>
            <button onclick="switchTab('review-tab')" id="btn-review-tab" class="tab-btn border-b-2 border-transparent px-6 py-4 text-sm font-bold opacity-60 hover:opacity-100 text-navy transition-all focus:outline-none flex items-center gap-2">
                <span>🔍</span> Detailed Review
                <span class="bg-[#253745]/10 text-[#253745] px-2 py-0.5 rounded-full text-xs font-bold">{{ $quiz->questions->count() }}</span>
            </button>
            <button onclick="switchTab('history-tab')" id="btn-history-tab" class="tab-btn border-b-2 border-transparent px-6 py-4 text-sm font-bold opacity-60 hover:opacity-100 text-navy transition-all focus:outline-none flex items-center gap-2">
                <span>🕒</span> Attempt History
            </button>
        </div>

        <!-- TAB 1: OVERVIEW & BENCHMARKS -->
        <div id="overview-tab" class="tab-content active">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
                <!-- Score Display and Benchmarking -->
                <div class="lg:col-span-5 flex flex-col gap-8">
                    <div class="score-display p-10 text-center flex flex-col items-center justify-center flex-grow">
                        <span class="label-caps opacity-50 mb-4 text-white/70">Total Score</span>
                        <div class="text-7xl font-black mb-2">{{ number_format($result->percentage, 1) }}%</div>
                        <div class="text-xl font-bold opacity-70">{{ $result->score }} / {{ $result->total_questions }} Points</div>
                        
                        <div class="w-full h-1 bg-white/10 rounded-full mt-10 mb-10 overflow-hidden">
                            <div class="h-full bg-white shadow-[0_0_15px_rgba(255,255,255,0.5)] transition-all duration-1000" style="width: {{ $result->percentage }}%"></div>
                        </div>
                        
                        <div class="grid grid-cols-2 w-full gap-4">
                            <div class="text-center">
                                <span class="label-caps opacity-40 block mb-1">Rank</span>
                                <span class="text-2xl font-black">#{{ $rank }}</span>
                            </div>
                            <div class="text-center">
                                <span class="label-caps opacity-40 block mb-1">Status</span>
                                <span class="text-2xl font-black uppercase">{{ $result->percentage >= 40 ? 'Pass' : 'Fail' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="card-surface p-8">
                        <h3 class="label-caps mb-6 text-navy">Benchmarking</h3>
                        <div class="space-y-6">
                            <div class="flex justify-between items-center">
                                <span class="font-bold opacity-60">Class Average</span>
                                <span class="font-black text-xl text-navy">{{ number_format(($avgScore / $result->total_questions) * 100, 1) }}%</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="font-bold opacity-60">Highest Score</span>
                                <span class="font-black text-xl text-emerald-600">{{ number_format(($maxScore / $result->total_questions) * 100, 1) }}%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Card -->
                <div class="lg:col-span-7">
                    <div class="card-surface p-10 h-full flex flex-col justify-between">
                        <div>
                            <h3 class="label-caps mb-10 text-navy">Comparative Performance</h3>
                            <div class="h-72 flex items-center justify-center">
                                <canvas id="performanceChart"></canvas>
                            </div>
                        </div>
                        
                        <div class="mt-12 flex gap-4">
                            <a href="{{ route('student.dashboard') }}" class="btn-action flex-1 py-5 text-center font-black uppercase tracking-widest text-sm block">
                                Back to Dashboard
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB 2: DETAILED REVIEW -->
        <div id="review-tab" class="tab-content">
            <div class="card-surface p-8 md:p-10">
                <div class="flex flex-wrap items-center justify-between mb-8 gap-4 border-b border-gray-100 pb-6">
                    <div>
                        <h3 class="label-caps text-navy text-xl">Detailed Answer Key</h3>
                        <p class="text-sm opacity-60 mt-1 font-semibold">Click any question header to review correct answers and your choices.</p>
                    </div>
                    <div class="flex gap-2">
                        <button onclick="toggleAllAccordions(true)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-navy font-bold rounded-lg text-xs transition">Expand All</button>
                        <button onclick="toggleAllAccordions(false)" class="px-3 py-1.5 bg-gray-100 hover:bg-gray-200 text-navy font-bold rounded-lg text-xs transition">Collapse All</button>
                    </div>
                </div>

                <div class="space-y-4">
                    @foreach($quiz->questions as $index => $question)
                        @php
                            $userAnswer = $userAnswers->get($question->id);
                            $selectedOption = $userAnswer ? $userAnswer->selected_option : null;
                            $isCorrect = $selectedOption == $question->correct_option;
                        @endphp
                        
                        <!-- Collapsible Question Accordion -->
                        <div class="border rounded-2xl overflow-hidden transition-all duration-200 border-gray-200/80 bg-white">
                            <!-- Accordion Header -->
                            <div onclick="toggleAccordion('q-{{ $question->id }}', this)" class="accordion-header px-6 py-5 flex items-center justify-between gap-4 cursor-pointer hover:bg-gray-50/50 transition-colors select-none">
                                <div class="flex items-center gap-4 min-w-0 flex-1">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs shrink-0 {{ $isCorrect ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700' }}">
                                        {{ $index + 1 }}
                                    </div>
                                    <p class="font-bold text-[#06141B] text-base truncate">{{ $question->question_text }}</p>
                                </div>
                                <div class="flex items-center gap-3 shrink-0">
                                    @if($isCorrect)
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            Correct
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 text-xs font-bold text-red-700 bg-red-50 px-2.5 py-1 rounded-md">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                            Incorrect
                                        </span>
                                    @endif
                                    <svg class="w-5 h-5 opacity-40 transform transition-transform duration-200 arrow-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>

                            <!-- Accordion Body -->
                            <div id="q-{{ $question->id }}" class="accordion-body hidden border-t border-gray-100 bg-[#F8F9F9] px-6 py-6 animate-in fade-in duration-200">
                                <p class="font-bold text-lg text-navy mb-5">{{ $question->question_text }}</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    @for($i = 1; $i <= 4; $i++)
                                        @php
                                            $optText = $question->{"option".$i};
                                            if(!$optText) continue;
                                            
                                            $isThisSelected = ($selectedOption == $i);
                                            $isThisCorrect = ($question->correct_option == $i);
                                            
                                            $optClass = "border-gray-200 bg-white opacity-70";
                                            if($isThisCorrect) {
                                                $optClass = "border-emerald-500 bg-emerald-50 text-emerald-900 font-bold shadow-sm ring-1 ring-emerald-500";
                                            } elseif($isThisSelected && !$isThisCorrect) {
                                                $optClass = "border-red-500 bg-red-50 text-red-900 shadow-sm ring-1 ring-red-500";
                                            }
                                        @endphp
                                        <div class="px-5 py-4 border rounded-xl {{ $optClass }}">
                                            <div class="flex items-center justify-between">
                                                <span class="text-sm font-semibold">{{ $optText }}</span>
                                                @if($isThisCorrect)
                                                    <span class="inline-flex items-center gap-1 text-emerald-600 font-bold text-xs uppercase tracking-wider">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                        Correct Answer
                                                    </span>
                                                @elseif($isThisSelected)
                                                    <span class="inline-flex items-center gap-1 text-red-600 font-bold text-xs uppercase tracking-wider">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                                                        Your Selection
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- TAB 3: ATTEMPT HISTORY -->
        <div id="history-tab" class="tab-content">
            <div class="card-surface overflow-hidden border border-white/20">
                <div class="p-8 border-b border-gray-100">
                    <h2 class="text-2xl font-extrabold tracking-tight text-[#06141B] mb-1">Your Assessment History</h2>
                    <p class="text-sm opacity-60 font-semibold">Review your performance trends over time.</p>
                </div>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 border-b border-gray-100">
                            <th class="px-8 py-5 label-caps text-xs">Quiz Title</th>
                            <th class="px-8 py-5 label-caps text-xs">Subject</th>
                            <th class="px-8 py-5 label-caps text-xs">Raw Score</th>
                            <th class="px-8 py-5 label-caps text-xs">Grade %</th>
                            <th class="px-8 py-5 label-caps text-xs text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($history as $item)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-8 py-5 font-bold text-[#06141B]">
                                    {{ $item->quiz->title }}
                                </td>
                                <td class="px-8 py-5">
                                    <span class="label-caps px-2.5 py-1 bg-gray-100 rounded text-[#06141B] opacity-80 text-[10px]">{{ $item->quiz->subject ?: 'General' }}</span>
                                </td>
                                <td class="px-8 py-5 font-bold text-gray-600">
                                    {{ $item->score }} / {{ $item->total_questions }}
                                </td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center gap-1 text-xs font-black px-2.5 py-1 rounded {{ $item->percentage >= 40 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                        {{ number_format($item->percentage, 1) }}%
                                        <span class="text-[9px] opacity-70 font-semibold">({{ $item->percentage >= 40 ? 'PASS' : 'FAIL' }})</span>
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('student.quizzes.result', $item->quiz_id) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-[#253745] hover:bg-[#1a2833] text-white rounded-lg text-xs font-bold transition shadow-sm">
                                        View Report
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-8 py-16 text-center text-gray-400 font-bold">
                                    No attempt history found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Scripting for Dynamic Interactions -->
    <script>
        // Tab switching logic
        function switchTab(tabId) {
            // Hide all tab contents
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            // Show selected tab content
            document.getElementById(tabId).classList.add('active');

            // Deactivate all tab buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'border-[#253745]');
                btn.classList.add('border-transparent', 'opacity-60');
            });
            // Activate current tab button
            const activeBtn = document.getElementById('btn-' + tabId);
            activeBtn.classList.add('active', 'border-[#253745]');
            activeBtn.classList.remove('border-transparent', 'opacity-60');
        }

        // Accordion toggle logic
        function toggleAccordion(id, el) {
            const body = document.getElementById(id);
            const arrow = el.querySelector('.arrow-icon');
            
            if (body.classList.contains('hidden')) {
                body.classList.remove('hidden');
                arrow.classList.add('rotate-180');
            } else {
                body.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }
        }

        // Expand/collapse all accordions
        function toggleAllAccordions(shouldExpand) {
            document.querySelectorAll('.accordion-body').forEach(body => {
                if (shouldExpand) {
                    body.classList.remove('hidden');
                } else {
                    body.classList.add('hidden');
                }
            });
            document.querySelectorAll('.arrow-icon').forEach(arrow => {
                if (shouldExpand) {
                    arrow.classList.add('rotate-180');
                } else {
                    arrow.classList.remove('rotate-180');
                }
            });
        }

        // ChartJS Rendering
        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Your Score', 'Class Average', 'Peak Performance'],
                datasets: [{
                    data: [
                        {{ $result->percentage }}, 
                        {{ ($avgScore / $result->total_questions) * 100 }},
                        {{ ($maxScore / $result->total_questions) * 100 }}
                    ],
                    backgroundColor: ['#253745', 'rgba(37, 55, 69, 0.2)', 'rgba(37, 55, 69, 0.1)'],
                    borderRadius: 12,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { weight: 'bold' } }
                    }
                }
            }
        });
    </script>
</body>
</html>
