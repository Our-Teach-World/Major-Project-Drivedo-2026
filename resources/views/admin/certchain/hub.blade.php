@extends('admin.layouts.app')
@section('title', 'CertChain Hub - CampusCore')
@section('header_title', 'CertChain Control Center')

@section('content')
<div class="max-w-6xl mx-auto">
    {{-- Header Banner --}}
    <div class="card bg-gradient-to-r from-[#253745] to-[#1a2833] text-[#CCD0CF] p-8 mb-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl border-none">
        <div class="space-y-2">
            <h2 class="font-extrabold text-2xl md:text-3xl text-white tracking-tight flex items-center gap-3">
                <span>⛓️</span> CertChain Hub
            </h2>
            <p class="text-sm text-gray-300 max-w-xl">
                Manage your institution's digital certificate ecosystem. Design templates, register academic events, issue cryptographic credentials, and inspect the live blockchain ledger.
            </p>
        </div>
        <div class="bg-white/10 px-4 py-2 rounded-2xl border border-white/10 text-xs font-bold uppercase tracking-wider text-yellow-300 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-green-400 animate-pulse"></span>
            Blockchain Ledger Active
        </div>
    </div>

    {{-- Hub Cards Grid --}}
    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
        @php
            $features = [
                [
                    'title' => '📜 Templates',
                    'desc' => 'Design, customize, and activate HTML/CSS certificate templates with responsive fields and custom styling.',
                    'route' => 'admin.certchain.templates.index',
                    'color' => 'from-blue-500/10 to-blue-500/5',
                    'border' => 'hover:border-blue-500/30',
                    'text_color' => 'text-blue-600',
                    'action' => 'Manage Templates'
                ],
                [
                    'title' => '📅 Events',
                    'desc' => 'Create and organize college events (hackathons, workshops, cultural events) for certificate associations.',
                    'route' => 'teacher.certchain.events.index',
                    'color' => 'from-purple-500/10 to-purple-500/5',
                    'border' => 'hover:border-purple-500/30',
                    'text_color' => 'text-purple-600',
                    'action' => 'Manage Events'
                ],
                [
                    'title' => '🏅 Issued List',
                    'desc' => 'View, filter, download PDFs, or revoke previously issued student certificates across all departments.',
                    'route' => 'teacher.certchain.certificates.index',
                    'color' => 'from-green-500/10 to-green-500/5',
                    'border' => 'hover:border-green-500/30',
                    'text_color' => 'text-green-600',
                    'action' => 'View Certificates'
                ],
                [
                    'title' => '📦 Bulk Issue',
                    'desc' => 'Generate and dispatch secure certificates to multiple students simultaneously via CSV/JSON auto-population.',
                    'route' => 'teacher.certchain.certificates.bulk',
                    'color' => 'from-amber-500/10 to-amber-500/5',
                    'border' => 'hover:border-amber-500/30',
                    'text_color' => 'text-amber-600',
                    'action' => 'Issue in Bulk'
                ],
                [
                    'title' => '⛓️ Ledger Status',
                    'desc' => 'Inspect cryptographic block integrity, read raw transaction hashes, and review mined block schedules.',
                    'route' => 'admin.certchain.blockchain',
                    'color' => 'from-rose-500/10 to-rose-500/5',
                    'border' => 'hover:border-rose-500/30',
                    'text_color' => 'text-rose-600',
                    'action' => 'Explore Ledger'
                ]
            ];
        @endphp

        @foreach($features as $feature)
            <div onclick="window.location.href='{{ route($feature['route']) }}'" 
                class="card p-6 flex flex-col justify-between cursor-pointer border border-gray-100 hover:shadow-xl hover:scale-[1.03] {{ $feature['border'] }} bg-gradient-to-br {{ $feature['color'] }} transition-all duration-300 group">
                <div class="space-y-4">
                    <div class="flex justify-between items-start">
                        <h3 class="font-extrabold text-lg text-[#06141B] flex items-center gap-2 group-hover:text-primary transition-colors">
                            {{ $feature['title'] }}
                        </h3>
                        <span class="text-xs font-bold {{ $feature['text_color'] }} opacity-0 group-hover:opacity-100 transition-opacity">
                            Go &rarr;
                        </span>
                    </div>
                    <p class="text-sm text-gray-500 leading-relaxed">
                        {{ $feature['desc'] }}
                    </p>
                </div>
                <div class="mt-6 pt-4 border-t border-gray-100/50 flex justify-between items-center text-xs">
                    <span class="text-gray-400 font-semibold">CertChain Control</span>
                    <button class="font-extrabold {{ $feature['text_color'] }} hover:underline group-hover:translate-x-1 transition-transform">
                        {{ $feature['action'] }} &rarr;
                    </button>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
