@extends('teacher.layout')

@section('page_title', 'Quiz Results - ' . $quiz->title)

@section('content')
<style>
    .workspace-bg { background-color: #CCD0CF; color: #06141B; border-radius: 24px; padding: 48px; }
    .card-surface {
        background-color: #FFFFFF;
        border-radius: 20px;
        box-shadow: 0px 4px 20px rgba(6, 20, 27, 0.04);
        border: 1px solid rgba(6, 20, 27, 0.02);
    }
    .metric-card {
        background-color: #253745;
        color: #CCD0CF;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0px 8px 20px rgba(37, 55, 69, 0.15);
    }
    .table-row:hover { background-color: #F8F9F9; }
    .label-caps { font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em; opacity: 0.5; }
    .badge-status { border-radius: 6px; padding: 4px 10px; font-size: 10px; font-weight: 800; }
</style>

<div class="workspace-bg min-h-[80vh]">
    <div class="mb-16">
        <a href="{{ route('teacher.quizzes.index') }}" class="inline-flex items-center gap-2 mb-6 text-sm font-bold opacity-60 hover:opacity-100 transition-opacity">
            <span class="material-symbols-outlined text-sm">arrow_back</span>
            BACK TO SESSIONS
        </a>
        <h2 class="text-4xl font-black tracking-tight text-[#06141B]">Performance Intelligence</h2>
        <p class="text-lg opacity-60 font-medium mt-2">Analytical breakdown for: <strong class="text-[#253745]">{{ $quiz->title }}</strong></p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
        <div class="metric-card">
            <p class="label-caps text-white/50 mb-2">PARTICIPANTS</p>
            <h3 class="text-4xl font-black">{{ $results->count() }}</h3>
        </div>
        <div class="metric-card bg-[#FFFFFF] text-[#06141B]">
            <p class="label-caps mb-2">AGGREGATE AVERAGE</p>
            <h3 class="text-4xl font-black text-[#253745]">{{ number_format($results->avg('percentage'), 1) }}%</h3>
        </div>
        <div class="metric-card">
            <p class="label-caps text-white/50 mb-2">PEAK SCORE</p>
            <h3 class="text-4xl font-black text-emerald-400">{{ $results->max('percentage') ?: 0 }}%</h3>
        </div>
    </div>

    <div class="card-surface overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-gray-50/50 border-b border-gray-100">
                    <th class="px-8 py-6 label-caps">CANDIDATE</th>
                    <th class="px-8 py-6 label-caps">RAW SCORE</th>
                    <th class="px-8 py-6 label-caps">PERCENTAGE</th>
                    <th class="px-8 py-6 label-caps">COMPLETION DATE</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($results as $res)
                    <tr class="table-row transition-colors">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-xl bg-[#253745] flex items-center justify-center text-white font-black text-xs shadow-md">
                                    {{ substr($res->user->username, 0, 1) }}
                                </div>
                                <span class="font-bold text-[#06141B] tracking-tight">{{ $res->user->username }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 font-bold text-[#06141B]/70">
                            {{ $res->score }} / {{ $res->total_questions }}
                        </td>
                        <td class="px-8 py-6">
                            <span class="badge-status {{ $res->percentage >= 70 ? 'bg-emerald-100 text-emerald-700' : ($res->percentage >= 40 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-700') }}">
                                {{ number_format($res->percentage, 1) }}%
                            </span>
                        </td>
                        <td class="px-8 py-6 text-sm font-bold opacity-40">
                            {{ $res->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-32 text-center">
                            <div class="opacity-20 flex flex-col items-center">
                                <span class="material-symbols-outlined text-5xl mb-4">analytics</span>
                                <p class="font-black uppercase tracking-widest text-sm">Waiting for candidate submissions</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
