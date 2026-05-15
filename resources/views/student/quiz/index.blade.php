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

        <!-- Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($quizzes as $quiz)
                <div class="card-surface p-8 flex flex-col h-full border border-white/20">
                    <div class="flex justify-between items-center mb-6">
                        <span class="label-caps px-3 py-1 bg-[#F2F4F3] rounded-md">{{ $quiz->subject ?: 'General' }}</span>
                        <div class="flex items-center gap-2 text-sm font-bold opacity-50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $quiz->duration_minutes }}m
                        </div>
                    </div>
                    
                    <h3 class="text-2xl font-bold text-navy mb-4">{{ $quiz->title }}</h3>
                    <p class="text-sm leading-relaxed opacity-60 mb-10 flex-grow">
                        Structured evaluation session containing {{ $quiz->questions_count }} targeted questions.
                    </p>
                    
                    <a href="{{ route('student.quizzes.take', $quiz) }}" class="btn-primary w-full py-4 text-center text-sm uppercase tracking-wider block">
                        Participate
                    </a>
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
    </div>
</body>
</html>