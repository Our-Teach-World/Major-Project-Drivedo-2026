@extends('admin.layouts.app')
@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')
@section('page-subtitle', 'System overview and college user directory statistics')

@section('header-actions')
<a href="{{ route('admin.add-user') }}" class="btn btn-primary text-sm">+ Add User</a>
@endsection

@section('content')
{{-- Welcome and Quick Info --}}
<div class="card p-6 mb-6 bg-gradient-to-r from-slate-800 to-slate-900 text-white border-none flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
    <div class="space-y-2">
        <h2 class="text-2xl font-bold tracking-tight">System Administration Overview</h2>
        <p class="text-slate-300 text-sm max-w-xl">Welcome to your admin panel dashboard. Here you can manage college users, approve registrations, update course schedules, and publish official notices.</p>
    </div>
    <div class="flex gap-3">
        <a href="{{ route('admin.users') }}" class="btn bg-white hover:bg-slate-100 text-slate-900 border-none text-sm">Manage Users</a>
        <a href="{{ route('admin.notices.create') }}" class="btn bg-blue-600 hover:bg-blue-700 text-white border-none text-sm">Publish Notice</a>
    </div>
</div>

{{-- Stats Grid --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">
    @php
    $statCards = [
        ['label'=>'Total Users',     'value'=>$totalUsers,    'icon'=>'👥', 'color'=>'text-blue-600 bg-blue-50 border-blue-100'],
        ['label'=>'Pending Users',   'value'=>$pendingUsers,  'icon'=>'⏳', 'color'=>'text-yellow-600 bg-yellow-50 border-yellow-100'],
        ['label'=>'Approved Users',  'value'=>$approvedUsers, 'icon'=>'✅', 'color'=>'text-green-600 bg-green-50 border-green-100'],
        ['label'=>'Teachers',        'value'=>$teachers,      'icon'=>'👨‍🏫', 'color'=>'text-purple-600 bg-purple-50 border-purple-100'],
        ['label'=>'Students',        'value'=>$students,      'icon'=>'🎓', 'color'=>'text-indigo-600 bg-indigo-50 border-indigo-100'],
        ['label'=>'Alumni',          'value'=>$alumni,        'icon'=>'💼', 'color'=>'text-teal-600 bg-teal-50 border-teal-100'],
    ];
    @endphp
    @foreach($statCards as $card)
    <div class="card p-5 hover:shadow-md transition-all border border-slate-100 flex flex-col justify-between mb-0">
        <div class="flex items-center justify-between mb-4">
            <span class="text-2xl">{{ $card['icon'] }}</span>
            <span class="text-xs px-2 py-0.5 rounded-full font-bold uppercase tracking-wider bg-slate-100 text-slate-600">Active</span>
        </div>
        <div>
            <p class="text-2xl font-black text-slate-800">{{ number_format($card['value']) }}</p>
            <p class="text-xs text-slate-500 font-semibold mt-1">{{ $card['label'] }}</p>
        </div>
    </div>
    @endforeach
</div>

{{-- Administration Quick Actions Grid --}}
<div class="grid md:grid-cols-2 xl:grid-cols-4 gap-6">
    <div class="card p-5 hover:shadow-md transition-all border border-slate-100 mb-0">
        <h3 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
            <span>👥</span> Users Control
        </h3>
        <p class="text-xs text-gray-500 mb-4">Add, edit, approve, or delete student, teacher, and alumni profiles.</p>
        <a href="{{ route('admin.users') }}" class="text-xs text-blue-600 font-bold hover:underline">Go to Users →</a>
    </div>

    <div class="card p-5 hover:shadow-md transition-all border border-slate-100 mb-0">
        <h3 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
            <span>📚</span> Subjects Management
        </h3>
        <p class="text-xs text-gray-500 mb-4">Manage college curriculum, subjects codes, and branch allocations.</p>
        <a href="{{ route('admin.subjects') }}" class="text-xs text-blue-600 font-bold hover:underline">Go to Subjects →</a>
    </div>

    <div class="card p-5 hover:shadow-md transition-all border border-slate-100 mb-0">
        <h3 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
            <span>🗓️</span> Timetable Schedules
        </h3>
        <p class="text-xs text-gray-500 mb-4">Configure semesters timetables and assign active slots.</p>
        <a href="{{ route('admin.timetable.setup') }}" class="text-xs text-blue-600 font-bold hover:underline">Go to Timetables →</a>
    </div>

    <div class="card p-5 hover:shadow-md transition-all border border-slate-100 mb-0">
        <h3 class="font-bold text-slate-800 mb-2 flex items-center gap-2">
            <span>📢</span> Board Notices
        </h3>
        <p class="text-xs text-gray-500 mb-4">Publish official college announcements and view board notices feed.</p>
        <a href="{{ route('admin.notices.create') }}" class="text-xs text-blue-600 font-bold hover:underline">Publish Notice →</a>
    </div>
</div>
@endsection
