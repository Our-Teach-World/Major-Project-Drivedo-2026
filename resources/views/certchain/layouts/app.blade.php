<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CertChain') — {{ config('app.college_name', env('COLLEGE_NAME','College')) }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                fontFamily: { sans: ['Inter','sans-serif'], heading: ['Space Grotesk','sans-serif'] },
                colors: {
                    primary:  { DEFAULT:'#1a3a5c', light:'#2a5298', dark:'#0f2139' },
                    gold:     { DEFAULT:'#c9a84c', light:'#e8c96b', dark:'#a07830' },
                }
            }
        }
    }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f0f4f8; }
        .sidebar { background: linear-gradient(180deg, #1a3a5c 0%, #0f2139 100%); }
        .nav-link { transition: all .2s; }
        .nav-link:hover, .nav-link.active { background: rgba(201,168,76,.15); border-left: 3px solid #c9a84c; color: #e8c96b; }
        .card { background: white; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,.08), 0 8px 20px rgba(0,0,0,.04); }
        .btn-primary { background: #1a3a5c; color: white; padding: .5rem 1.25rem; border-radius: 8px; font-weight: 500; transition: all .2s; }
        .btn-primary:hover { background: #2a5298; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(26,58,92,.3); }
        .btn-gold { background: #c9a84c; color: white; padding: .5rem 1.25rem; border-radius: 8px; font-weight: 500; transition: all .2s; }
        .btn-gold:hover { background: #a07830; }
        .badge-verified { background:#dcfce7; color:#16a34a; }
        .badge-revoked  { background:#fee2e2; color:#dc2626; }
        .badge-pending  { background:#fef9c3; color:#ca8a04; }
        .blockchain-node { background: linear-gradient(135deg,#1a3a5c,#2a5298); }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen flex">

{{-- Sidebar --}}
<aside class="sidebar w-64 min-h-screen flex flex-col fixed top-0 left-0 z-30">
    {{-- Logo --}}
    <div class="px-6 py-5 border-b border-white/10">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-lg bg-gold/20 flex items-center justify-center text-gold font-bold text-lg">⛓</div>
            <div>
                <p class="text-white font-heading font-bold text-sm leading-tight">CertChain</p>
                <p class="text-white/40 text-xs">Blockchain Certificates</p>
            </div>
        </div>
    </div>

    {{-- User Info --}}
    <div class="px-6 py-4 border-b border-white/10">
        <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
        <p class="text-white/40 text-xs">{{ ucfirst(auth()->user()->role_name) }}</p>
        <p class="text-white/30 text-xs truncate">{{ auth()->user()->department }}</p>
    </div>

    {{-- Nav --}}
    <nav class="flex-1 px-3 py-4 space-y-1">
        @if(auth()->user()->hasRole('admin'))
        <p class="text-white/30 text-xs px-3 mb-2 uppercase tracking-widest">Admin</p>
        <a href="{{ route('admin.dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <span>📊</span> Dashboard
        </a>
        <a href="{{ route('admin.users') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <span>👥</span> Manage Users
        </a>
        <a href="{{ route('admin.templates') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('admin.templates*') ? 'active' : '' }}">
            <span>🎨</span> Templates
        </a>
        <a href="{{ route('admin.blockchain') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('admin.blockchain') ? 'active' : '' }}">
            <span>⛓</span> Blockchain Ledger
        </a>
        <hr class="border-white/10 my-2">
        @endif

        <p class="text-white/30 text-xs px-3 mb-2 uppercase tracking-widest">Certificates</p>
        <a href="{{ route('faculty.dashboard') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('faculty.dashboard') ? 'active' : '' }}">
            <span>🏠</span> My Dashboard
        </a>
        <a href="{{ route('events.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('events*') ? 'active' : '' }}">
            <span>📅</span> Events
        </a>
        <a href="{{ route('certificates.create') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('certificates.create') ? 'active' : '' }}">
            <span>➕</span> Issue Certificate
        </a>
        <a href="{{ route('certificates.bulk') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('certificates.bulk') ? 'active' : '' }}">
            <span>📦</span> Bulk Issue
        </a>
        <a href="{{ route('certificates.index') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('certificates.index') ? 'active' : '' }}">
            <span>📜</span> All Certificates
        </a>

        <hr class="border-white/10 my-2">
        <a href="{{ route('verify.index') }}" target="_blank" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm">
            <span>🔍</span> Verify Portal
        </a>
        <a href="{{ route('faculty.profile') }}" class="nav-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/70 text-sm {{ request()->routeIs('faculty.profile') ? 'active' : '' }}">
            <span>👤</span> My Profile
        </a>
    </nav>

    {{-- Logout --}}
    <div class="px-4 py-4 border-t border-white/10">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2 rounded-lg text-white/50 text-sm hover:text-red-400 transition-colors">
                <span>🚪</span> Logout
            </button>
        </form>
    </div>
</aside>

{{-- Main Content --}}
<div class="ml-64 flex-1 flex flex-col min-h-screen">
    {{-- Top bar --}}
    <header class="bg-white border-b border-gray-100 px-8 py-4 flex items-center justify-between sticky top-0 z-20">
        <div>
            <h1 class="font-heading font-bold text-gray-800 text-lg">@yield('page-title', 'Dashboard')</h1>
            <p class="text-gray-400 text-xs">@yield('page-subtitle', '')</p>
        </div>
        <div class="flex items-center gap-3">
            @yield('header-actions')
        </div>
    </header>

    {{-- Flash Messages --}}
    <div class="px-8 pt-4">
        @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2 mb-4">
            <span>✅</span> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm flex items-center gap-2 mb-4">
            <span>❌</span> {{ session('error') }}
        </div>
        @endif
        @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg text-sm mb-4">
            <ul class="list-disc list-inside space-y-1">
                @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
            </ul>
        </div>
        @endif
    </div>

    {{-- Page Content --}}
    <main class="flex-1 px-8 py-4 pb-10">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
