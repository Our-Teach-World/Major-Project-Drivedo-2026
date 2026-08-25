<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Certificate — CertChain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body style="background: linear-gradient(135deg, #0f2139 0%, #1a3a5c 60%, #2a5298 100%); min-height: 100vh;" class="flex items-center justify-center px-4 py-10">

<div class="w-full max-w-lg">
    {{-- Header --}}
    <div class="text-center mb-8">
        <div class="w-16 h-16 rounded-2xl bg-white/10 flex items-center justify-center text-4xl mx-auto mb-4 backdrop-blur-sm">⛓</div>
        <h1 class="text-3xl font-bold text-white" style="font-family:'Space Grotesk',sans-serif;">CertChain Verify</h1>
        <p class="text-blue-200 mt-2">Blockchain Certificate Verification Portal</p>
        <p class="text-blue-300/60 text-xs mt-1">{{ config('app.college_name', env('COLLEGE_NAME','Your College')) }}</p>
    </div>

    {{-- Search Card --}}
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="font-semibold text-gray-800 mb-1">Verify a Certificate</h2>
        <p class="text-sm text-gray-500 mb-5">Enter the student's enrollment number or the certificate ID to verify authenticity.</p>

        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
            <span>❌</span> {{ session('error') }}
        </div>
        @endif

        <form method="POST" action="{{ route('verify.search') }}">
            @csrf
            <div class="mb-4">
                <input type="text" name="query" autofocus required
                    placeholder="e.g. 0801CS211001  or  CERT-2024-AB1234"
                    class="w-full border-2 border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 transition">
                @error('query')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
            <button type="submit"
                class="w-full py-3 bg-gradient-to-r from-blue-900 to-blue-600 text-white rounded-xl font-semibold text-sm hover:from-blue-800 transition-all shadow-lg shadow-blue-900/20">
                🔍 Verify Certificate
            </button>
        </form>

        <div class="mt-6 pt-6 border-t border-gray-100 flex items-center justify-between">
            <p class="text-xs text-gray-400">Are you a faculty member?</p>
            <a href="{{ route('login') }}" class="text-xs text-blue-600 hover:underline font-medium">Login to Dashboard →</a>
        </div>
    </div>

    {{-- Info Cards --}}
    <div class="grid grid-cols-3 gap-3 mt-5">
        @foreach([['🔒','Tamper-Proof','SHA-256 blockchain'],['⚡','Instant','Real-time results'],['🌐','Public','No login needed']] as [$icon,$title,$sub])
        <div class="bg-white/10 backdrop-blur-sm rounded-xl p-3 text-center text-white">
            <p class="text-xl mb-1">{{ $icon }}</p>
            <p class="text-xs font-semibold">{{ $title }}</p>
            <p class="text-white/50 text-xs">{{ $sub }}</p>
        </div>
        @endforeach
    </div>
</div>

</body>
</html>
