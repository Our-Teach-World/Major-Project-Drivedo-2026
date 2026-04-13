@extends('admin.layouts.app')

@section('title', 'Principal Dashboard - EduShare')

@section('header_title', 'EduShare Principal Panel')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h1 style="font-weight: 900; letter-spacing: -1px; margin-bottom: 30px; text-transform: uppercase;">
            College Overview
        </h1>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; margin-bottom: 40px;">
    <!-- Total Students Card -->
    <div style="background: #fff; border: 4px solid #000; padding: 30px; box-shadow: 10px 10px 0px #000; transition: transform 0.2s; cursor: default;">
        <div style="font-size: 1.2rem; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; color: #555;">Total Students</div>
        <div style="font-size: 4rem; font-weight: 900; line-height: 1;">{{ $totalStudents }}</div>
        <div style="margin-top: 15px; font-weight: 700; color: #10b981;">↑ Across All Branches</div>
    </div>

    <!-- Total Teachers Card -->
    <div style="background: #fff; border: 4px solid #000; padding: 30px; box-shadow: 10px 10px 0px #000; transition: transform 0.2s; cursor: default;">
        <div style="font-size: 1.2rem; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; color: #555;">Total Teachers</div>
        <div style="font-size: 4rem; font-weight: 900; line-height: 1;">{{ $totalTeachers }}</div>
        <div style="margin-top: 15px; font-weight: 700; color: #3b82f6;">🎓 Verified Faculty</div>
    </div>

    <!-- Total Branches Card -->
    <div style="background: #fff; border: 4px solid #000; padding: 30px; box-shadow: 10px 10px 0px #000; transition: transform 0.2s; cursor: default;">
        <div style="font-size: 1.2rem; font-weight: 800; text-transform: uppercase; margin-bottom: 10px; color: #555;">Active Branches</div>
        <div style="font-size: 4rem; font-weight: 900; line-height: 1;">{{ $branches }}</div>
        <div style="margin-top: 15px; font-weight: 700; color: #f59e0b;">🏛️ Engineering Depts</div>
    </div>
</div>


    <div class="col-md-6">
        <div style="background: #000; color: #fff; border: 4px solid #000; padding: 30px; box-shadow: 8px 8px 0px #333;">
            <h3 style="font-weight: 900; margin-bottom: 20px; text-transform: uppercase; color: #fff;">Principal's Message</h3>
            <p style="font-size: 1.1rem; font-weight: 600; line-height: 1.6; opacity: 0.9;">
                Welcome to the EduShare Command Center. As the Principal, you have complete oversight over all departments, faculty, and students. Use the controls to maintain academic excellence and ensure smooth communication across the institution.
            </p>
            <div style="margin-top: 20px; font-weight: 800; font-size: 1.2rem; border-top: 1px solid #333; pt: 15px;">
                - EduShare System
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn:hover {
        transform: translate(-3px, -3px);
        box-shadow: 5px 5px 0px #333;
    }
</style>
@endpush
@endsection
