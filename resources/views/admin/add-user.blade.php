@extends('admin.layouts.app')

@section('title', 'Add New User - EduShare Admin')
@section('header_title', '➕ Add New User')

@push('styles')
    <style>
        .container {
            max-width: 500px;
            margin: 50px auto;
        }

        .form-card {
            background-color: #ffffff;
            border: 2px solid #000000;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 4px 4px 0px #000;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #000000;
        }

        input[type="text"],
        input[type="password"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #000000;
            border-radius: 5px;
            font-size: 1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        input[type="text"]:focus,
        input[type="password"]:focus,
        select:focus {
            outline: none;
            background-color: #f0f0f0;
        }

        .button-group {
            display: flex;
            gap: 15px;
            margin-top: 25px;
        }

        .btn-submit {
            flex: 1;
            padding: 12px;
            background-color: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            font-size: 1rem;
            transition: 0.3s;
        }

        .btn-submit:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .btn-cancel {
            flex: 1;
            padding: 12px;
            background-color: #ffffff;
            color: #000000;
            border: 2px solid #000000;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            font-size: 1rem;
            transition: 0.3s;
        }

        .btn-cancel:hover {
            background-color: #000000;
            color: #ffffff;
        }

        .alert-error {
            background-color: #ffebee;
            border-color: #c62828;
            color: #c62828;
        }

        @media (max-width: 768px) {
            .form-card { padding: 20px; }
            .button-group { flex-direction: column; }
        }
    </style>
@endpush

@section('content')

    <div class="container">
        <div class="form-card">
            <h1 style="text-align:center; margin-bottom: 25px;">Create New User</h1>

            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Please fix the following errors:</strong>
                    <ul style="margin-left: 20px; margin-top: 10px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.add-user.post') }}">
                @csrf

                <div class="form-group">
                    <label for="username">Full Name *</label>
                    <input type="text" id="username" name="username" placeholder="Minimum 6 characters" 
                        minlength="6" required value="{{ old('username') }}">
                </div>

                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required onchange="toggleEnrollment(this.value)">
                        <option value="">-- Select Role --</option>
                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="alumni" {{ old('role') == 'alumni' ? 'selected' : '' }}>Alumni</option>
                    </select>
                </div>

                <div class="form-group" id="enrollment-group" style="display: {{ old('role') == 'student' ? 'block' : 'none' }};">
                    <label for="enrollment_no">Enrollment Number *</label>
                    <input type="text" id="enrollment_no" name="enrollment_no" value="{{ old('enrollment_no') }}" placeholder="e.g. 23010101001">
                </div>

                <script>
                    function toggleEnrollment(role) {
                        const group = document.getElementById('enrollment-group');
                        const input = document.getElementById('enrollment_no');
                        if (role === 'student') {
                            group.style.display = 'block';
                            input.required = true;
                        } else {
                            group.style.display = 'none';
                            input.required = false;
                        }
                    }
                </script>

                <div class="form-group">
                    <label for="password">Password *</label>
                    <input type="password" id="password" name="password" placeholder="Minimum 4 characters" 
                        minlength="4" required>
                </div>

                <div class="form-group">
                    <label style="color: #666; font-size: 0.9rem;">Status: Pending (will be set automatically)</label>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-submit">Create User</button>
                    <a href="{{ route('admin.users') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

@endsection
