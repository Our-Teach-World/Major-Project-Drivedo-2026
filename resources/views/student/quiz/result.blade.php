<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessment Result - EduShare</title>
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
    </style>
</head>
<body class="min-h-screen px-6 py-16 md:px-20">
    <div class="max-w-5xl mx-auto">
        <header class="mb-12 text-center md:text-left">
            <a href="{{ route('student.quizzes.index') }}" class="inline-flex items-center gap-2 mb-6 text-sm font-bold opacity-60 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Sessions Overview
            </a>
            <h1 class="text-4xl font-extrabold tracking-tight text-[#06141B]">Performance Analytics</h1>
            <p class="text-lg opacity-70 mt-2">{{ $quiz->title }}</p>
        </header>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">
            <!-- Score Card -->
            <div class="lg:col-span-5 flex flex-col gap-8">
                <div class="score-display p-10 text-center flex flex-col items-center justify-center flex-grow">
                    <span class="label-caps opacity-50 mb-4">Total Score</span>
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
                    <h3 class="label-caps mb-6">Benchmarking</h3>
                    <div class="space-y-6">
                        <div class="flex justify-between items-center">
                            <span class="font-bold opacity-60">Class Average</span>
                            <span class="font-black text-xl">{{ number_format(($avgScore / $result->total_questions) * 100, 1) }}%</span>
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
                <div class="card-surface p-10 h-full flex flex-col">
                    <h3 class="label-caps mb-10">Comparative Performance</h3>
                    <div class="flex-grow flex items-center justify-center">
                        <canvas id="performanceChart"></canvas>
                    </div>
                    
                    <div class="mt-12 flex flex-col sm:flex-row gap-4">
                        <a href="{{ route('student.dashboard') }}" class="btn-action flex-1 py-5 text-center font-black uppercase tracking-widest text-sm">
                            Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
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
