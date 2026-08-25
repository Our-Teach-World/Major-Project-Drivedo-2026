<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — CertChain</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Space+Grotesk:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex" style="background: linear-gradient(135deg, #0f2139 0%, #1a3a5c 50%, #2a5298 100%);">

    {{-- Left Panel --}}
    <div class="hidden lg:flex flex-col justify-center px-16 w-1/2 text-white">
        <div class="mb-10">
            <div class="w-16 h-16 rounded-2xl bg-yellow-400/20 flex items-center justify-center text-4xl mb-6">⛓</div>
            <h1 class="text-4xl font-bold mb-3" style="font-family:'Space Grotesk',sans-serif;">CertChain</h1>
            <p class="text-blue-200 text-lg leading-relaxed">Blockchain-powered Certificate Issuing<br>& Verification System</p>
        </div>
        <div class="space-y-4">
            <div class="flex items-start gap-4 bg-white/5 rounded-xl p-4 backdrop-blur-sm">
                <span class="text-2xl">🔒</span>
                <div>
                    <p class="font-semibold text-sm">Tamper-Proof Certificates</p>
                    <p class="text-blue-300 text-xs mt-1">Every certificate is SHA-256 hashed and chained — impossible to forge</p>
                </div>
            </div>
            <div class="flex items-start gap-4 bg-white/5 rounded-xl p-4 backdrop-blur-sm">
                <span class="text-2xl">📧</span>
                <div>
                    <p class="font-semibold text-sm">Instant Email Delivery</p>
                    <p class="text-blue-300 text-xs mt-1">Auto-email PDF certificates to students upon issuance</p>
                </div>
            </div>
            <div class="flex items-start gap-4 bg-white/5 rounded-xl p-4 backdrop-blur-sm">
                <span class="text-2xl">🔍</span>
                <div>
                    <p class="font-semibold text-sm">Public Verification</p>
                    <p class="text-blue-300 text-xs mt-1">Anyone can verify a certificate using enrollment number or cert ID</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Right Panel / Form --}}
    <div class="flex-1 flex items-center justify-center px-8">
        <div class="bg-white rounded-2xl shadow-2xl p-10 w-full max-w-md">
            <div class="mb-8 text-center">
                <div class="w-12 h-12 rounded-xl bg-blue-900/10 flex items-center justify-center text-2xl mx-auto mb-4">🎓</div>
                <h2 class="text-2xl font-bold text-gray-800" style="font-family:'Space Grotesk',sans-serif;">Welcome Back</h2>
                <p class="text-gray-500 text-sm mt-1">Sign in to your CertChain account</p>
            </div>

            @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg mb-5">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email Address</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="you@college.edu">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        class="w-full border border-gray-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition"
                        placeholder="••••••••">
                </div>
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300">
                        Remember me
                    </label>
                </div>
                <button type="submit"
                    class="w-full py-2.5 bg-gradient-to-r from-blue-900 to-blue-700 text-white rounded-lg font-semibold text-sm hover:from-blue-800 hover:to-blue-600 transition-all shadow-lg shadow-blue-900/20">
                    Sign In to CertChain
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100 text-center">
                <a href="{{ route('verify.index') }}" class="text-sm text-blue-600 hover:underline">
                    🔍 Verify a certificate without logging in →
                </a>
            </div>
        </div>
    </div>
</body>
</html>
