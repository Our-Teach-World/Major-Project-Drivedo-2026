@extends('admin.layouts.app')

@section('title', 'Publish Notice - CampusCore Admin')
@section('header_title', '📢 Publish Notice')

@section('content')
<style>
    .notice-container {
        max-width: 800px;
        margin: 0 auto;
        background: #ffffff;
        border: 2px solid #000000;
        border-radius: 8px;
        padding: 35px;
        box-shadow: 6px 6px 0px rgba(0, 0, 0, 1);
    }
    .form-group { margin-bottom: 22px; }
    label { display: block; font-weight: 800; margin-bottom: 8px; font-size: 0.95rem; }
    
    input[type="text"], textarea, select {
        width: 100%; padding: 14px; border: 2px solid #000000;
        border-radius: 5px; font-size: 1rem; background: #fafafa;
        transition: box-shadow 0.2s;
    }
    input[type="text"]:focus, textarea:focus, select:focus {
        outline: none; box-shadow: 0 0 0 3px rgba(0,0,0,0.1); background: #fff;
    }
    
    textarea { resize: vertical; min-height: 140px; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }

    .file-drop-area {
        border: 2px dashed #000000; border-radius: 5px; padding: 30px;
        text-align: center; cursor: pointer; background: #f9f9f9;
        transition: 0.3s;
    }
    .file-drop-area:hover { background: #e0e0e0; }

    .btn-submit {
        background: #000000; color: #ffffff; border: 2px solid #000000;
        padding: 15px 24px; font-size: 1.1rem; font-weight: 800;
        border-radius: 5px; cursor: pointer; width: 100%; transition: 0.3s;
        text-transform: uppercase; letter-spacing: 1px;
    }
    .btn-submit:hover { background: #ffffff; color: #000000; }

    .alert { padding: 15px; margin-bottom: 20px; border: 2px solid #000; border-radius: 5px; font-weight: 700; }
    .alert-success { background: #d1fae5; color: #065f46; border-color: #065f46; }
    .alert-error { background: #fee2e2; color: #991b1b; border-color: #991b1b; }
</style>

<div class="notice-container">
    @if($admin->role === 'principal')
        <h2 style="font-weight: 900; margin-bottom: 5px; font-size: 2.2rem;">📢 Global College-wide Notice</h2>
    @else
        <h2 style="font-weight: 900; margin-bottom: 5px; font-size: 2.2rem;">📢 Publish Department Notice</h2>
    @endif
    <p style="color: #555; font-weight: 600; margin-bottom: 30px;">Instantly trigger a Push Notification to {{ $admin->role === 'principal' ? 'all' : 'designated' }} students.</p>

    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-error">
            ❌ <strong>Whoops! Check your inputs:</strong>
            <ul style="margin-top: 8px; margin-left: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @php
        $routeName = request()->route()->getName();
        $storeRoute = str_replace('.create', '.store', $routeName);
    @endphp

    <form action="{{ route($storeRoute) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label>Notice Title *</label>
            <input type="text" name="title" placeholder="e.g. Tomorrow is a Public Holiday" required value="{{ old('title') }}">
        </div>

        <div class="form-group">
            <label>Notice Content *</label>
            <textarea name="content" placeholder="Type the full notice message here..." required>{{ old('content') }}</textarea>
        </div>

        <div class="stats-grid" style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; margin-bottom: 22px;">
            <div class="form-group">
                <label>Target Audience</label>
                <select name="target_role" id="target_role" onchange="toggleFilters()">
                    <option value="student" {{ old('target_role') == 'student' ? 'selected' : '' }}>Students</option>
                    @if($admin->role === 'principal')
                        <option value="teacher" {{ old('target_role') == 'teacher' ? 'selected' : '' }}>Faculty (Teachers)</option>
                        <option value="hod" {{ old('target_role') == 'hod' ? 'selected' : '' }}>HODs / Department Heads</option>
                        <option value="all" {{ old('target_role') == 'all' ? 'selected' : '' }}>Whole College (Everyone)</option>
                    @else
                        <option value="teacher" {{ old('target_role') == 'teacher' ? 'selected' : '' }}>Department Faculty</option>
                    @endif
                </select>
            </div>

            <div class="form-group" id="branch_filter">
                <label>Target Branch</label>
                @if($admin->role === 'principal')
                    <select name="target_branch">
                        <option value="">All Branches</option>
                        @foreach($branches as $b)
                            <option value="{{ $b }}" {{ old('target_branch') == $b ? 'selected' : '' }}>{{ $b }}</option>
                        @endforeach
                    </select>
                @else
                    <div style="padding: 14px; border: 2px solid #000; border-radius: 5px; background: #e2e8f0; font-weight: 800; font-size: 1rem;">
                        📍 {{ $admin->branch }}
                    </div>
                    <input type="hidden" name="target_branch" value="{{ $admin->branch }}">
                @endif
            </div>

            <div class="form-group" id="semester_filter">
                <label>Target Semester</label>
                <select name="target_semester">
                    <option value="">All Semesters</option>
                    @for($i=1; $i<=6; $i++)
                        <option value="{{ $i }}" {{ old('target_semester') == $i ? 'selected' : '' }}>Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <script>
            function toggleFilters() {
                const role = document.getElementById('target_role').value;
                const semFilter = document.getElementById('semester_filter');
                if (role === 'teacher' || role === 'hod' || role === 'all') {
                    semFilter.style.opacity = '0.5';
                    semFilter.style.pointerEvents = 'none';
                } else {
                    semFilter.style.opacity = '1';
                    semFilter.style.pointerEvents = 'auto';
                }
            }
            document.addEventListener('DOMContentLoaded', toggleFilters);
        </script>

        <div class="form-group">
            <label>Attachment (PDF/Image - Optional)</label>
            <div class="file-drop-area" onclick="document.getElementById('attachment').click()">
                <div style="font-size: 2.5rem; margin-bottom: 10px;">📎</div>
                <div style="font-weight: 800;">Click to attach a file</div>
                <div style="font-size: 0.85rem; color: #666; margin-top: 5px; font-weight: 600;" id="file-name">Max size: 5MB</div>
            </div>
            <input type="file" name="attachment" id="attachment" style="display: none;" accept=".pdf,.jpg,.jpeg,.png" onchange="document.getElementById('file-name').innerText = 'Selected: ' + this.files[0].name">
        </div>

        <button type="submit" class="btn-submit">🚀 Publish & Ping Students</button>
    </form>
</div>
@endsection