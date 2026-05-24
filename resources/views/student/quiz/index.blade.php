<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Quizzes - CampusCore</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #CCD0CF; 
            color: #06141B;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }
        .header-bg { background-color: #CCD0CF; }
        .card-surface {
            background-color: #FFFFFF;
            border-radius: 16px;
            box-shadow: 0px 4px 10px rgba(6, 20, 27, 0.04);
            transition: transform 0.2s cubic-bezier(0.17, 0.67, 0.83, 0.67), box-shadow 0.2s;
        }
        .card-surface:hover {
            transform: translateY(-2px);
            box-shadow: 0px 10px 20px rgba(6, 20, 27, 0.08);
        }
        .btn-primary {
            background-color: #253745;
            color: #CCD0CF;
            border-radius: 8px;
            font-weight: 600;
            letter-spacing: 0.01em;
            transition: background-color 0.2s;
        }
        .btn-primary:hover {
            background-color: #1a2833;
        }
        .label-caps {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #06141B;
            opacity: 0.6;
        }
        .text-navy { color: #06141B; }
        .bg-navy { background-color: #06141B; }
    </style>
</head>
<body class="min-h-screen px-6 py-12 md:px-20">
    <div class="max-w-6xl mx-auto">
        <!-- Header -->
        <header class="mb-16">
            <a href="{{ route('student.dashboard') }}" class="inline-flex items-center gap-2 mb-6 text-sm font-bold opacity-60 hover:opacity-100 transition-opacity">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Back to Dashboard
            </a>
            <h1 class="text-4xl font-extrabold tracking-tight text-navy mb-3">Academic Sessions</h1>
            <p class="text-lg opacity-70">Browse and participate in active assessments.</p>
        </header>

        <!-- Filters Form -->
        <form action="{{ route('student.quizzes.index') }}" method="GET" class="card-surface p-6 mb-12 border border-white/20">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 items-end">
                <!-- Subject Filter -->
                <div>
                    <label for="subject" class="block label-caps mb-2 text-xs">Subject</label>
                    <select name="subject" id="subject" class="w-full bg-[#F2F4F3] border border-[#253745]/10 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#253745] text-navy font-medium">
                        <option value="">All Subjects</option>
                        @foreach($subjects as $subj)
                            <option value="{{ $subj }}" {{ request('subject') == $subj ? 'selected' : '' }}>{{ $subj }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label for="date" class="block label-caps mb-2 text-xs">Created Date</label>
                    <input type="date" name="date" id="date" value="{{ request('date') }}" class="w-full bg-[#F2F4F3] border border-[#253745]/10 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#253745] text-navy font-medium">
                </div>

                <!-- Status Filter -->
                <div>
                    <label for="status" class="block label-caps mb-2 text-xs">Status</label>
                    <select name="status" id="status" class="w-full bg-[#F2F4F3] border border-[#253745]/10 rounded-lg px-4 py-3 text-sm focus:outline-none focus:border-[#253745] text-navy font-medium">
                        <option value="">All Statuses</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="uncompleted" {{ request('status') == 'uncompleted' ? 'selected' : '' }}>Uncompleted</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit" class="flex-1 btn-primary py-3.5 text-center text-xs uppercase tracking-wider block font-bold">
                        Filter
                    </button>
                    @if(request()->filled('subject') || request()->filled('date') || request()->filled('status'))
                        <a href="{{ route('student.quizzes.index') }}" class="px-4 py-3 bg-red-100 hover:bg-red-200 text-red-700 rounded-lg text-sm font-semibold flex items-center justify-center transition-colors" title="Clear Filters">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </a>
                    @endif
                </div>
            </div>
        </form>

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($quizzes as $quiz)
                <div class="card-surface p-8 flex flex-col h-full border border-white/20">
                    <div class="flex justify-between items-center mb-6">
                        <span class="label-caps px-3 py-1 bg-[#F2F4F3] rounded-md">{{ $quiz->subject ?: 'General' }}</span>
                        <div class="flex items-center gap-3">
                            @if($quiz->completed)
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-emerald-700 bg-emerald-50 px-2.5 py-1 rounded-md">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Done
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 text-xs font-bold text-amber-700 bg-amber-50 px-2.5 py-1 rounded-md">
                                    Pending
                                </span>
                            @endif
                            <div class="flex items-center gap-2 text-sm font-bold opacity-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $quiz->duration_minutes }}m
                            </div>
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-navy mb-4">{{ $quiz->title }}</h3>
                    <p class="text-sm leading-relaxed opacity-60 mb-10 flex-grow">
                        Structured evaluation session containing {{ $quiz->questions_count }} targeted questions.
                    </p>
                    
                    @if($quiz->completed)
                        <a href="{{ route('student.quizzes.result', $quiz) }}" class="w-full py-4 text-center text-sm uppercase tracking-wider block rounded-lg font-bold bg-emerald-600 hover:bg-emerald-700 text-white transition-colors">
                            View Result
                        </a>
                    @else
                        <a href="{{ route('student.quizzes.take', $quiz) }}" class="btn-primary w-full py-4 text-center text-sm uppercase tracking-wider block">
                            Participate
                        </a>
                    @endif
                </div>
            @empty
                <div class="col-span-full py-32 text-center">
                    <div class="w-20 h-20 bg-white/30 rounded-full flex items-center justify-center mx-auto mb-6">
                        <svg class="w-10 h-10 opacity-30" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold opacity-40">No assessments found</h3>
                    <p class="opacity-30">Contact your instructor if you believe this is an error.</p>
                </div>
            @endforelse
        </div>

        @if($quizzes->hasPages())
            <div class="mt-16 flex justify-center">
                {{ $quizzes->links() }}
            </div>
        @endif

        <!-- History Section -->
        <div class="mt-24 mb-12">
            <h2 class="text-3xl font-extrabold tracking-tight text-navy mb-3">Your Quiz Participation History</h2>
            <p class="text-md opacity-70 mb-8">Review your past scores, percentages, and performance details.</p>

            <div class="card-surface overflow-hidden border border-white/20">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#F2F4F3]/50 border-b border-[#253745]/10">
                            <th class="px-8 py-6 label-caps text-xs">Assessment Name</th>
                            <th class="px-8 py-6 label-caps text-xs">Subject</th>
                            <th class="px-8 py-6 label-caps text-xs">Raw Score</th>
                            <th class="px-8 py-6 label-caps text-xs">Grade %</th>
                            <th class="px-8 py-6 label-caps text-xs">Date Completed</th>
                            <th class="px-8 py-6 label-caps text-xs text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#253745]/5">
                        @forelse($history as $item)
                            <tr class="hover:bg-[#F2F4F3]/30 transition-colors">
                                <td class="px-8 py-6 font-bold text-navy">
                                    {{ $item->quiz->title }}
                                </td>
                                <td class="px-8 py-6">
                                    <span class="label-caps px-2.5 py-1 bg-[#F2F4F3] rounded text-navy opacity-80">{{ $item->quiz->subject ?: 'General' }}</span>
                                </td>
                                <td class="px-8 py-6 font-bold text-navy opacity-70">
                                    {{ $item->score }} / {{ $item->total_questions }}
                                </td>
                                <td class="px-8 py-6">
                                    <span class="inline-flex items-center gap-1 text-xs font-bold px-2.5 py-1 rounded-md {{ $item->percentage >= 40 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                                        {{ number_format($item->percentage, 1) }}%
                                        <span class="text-[10px] opacity-70 font-semibold">({{ $item->percentage >= 40 ? 'PASS' : 'FAIL' }})</span>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-sm font-semibold opacity-50">
                                    {{ $item->created_at->format('M d, Y') }}
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <a href="{{ route('student.quizzes.result', $item->quiz_id) }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-[#253745] hover:bg-[#1a2833] text-white rounded-lg text-xs font-bold transition-all shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                        View Analysis
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-20 text-center">
                                    <div class="w-16 h-16 bg-[#F2F4F3] rounded-full flex items-center justify-center mx-auto mb-4 opacity-50">
                                        <svg class="w-8 h-8 opacity-40 text-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    </div>
                                    <h4 class="font-bold text-navy opacity-45">No quiz history available</h4>
                                    <p class="text-sm opacity-35 mt-1">Complete your first active session to begin tracking performance.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>