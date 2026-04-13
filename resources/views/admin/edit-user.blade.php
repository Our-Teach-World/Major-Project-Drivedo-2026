@extends('admin.layouts.app')

@section('title', 'Edit User - EduShare Admin')
@section('header_title', '⚙️ Edit User')

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

        .user-info {
            background-color: #f9f9f9;
            border: 2px solid #000;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 0.95rem;
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
        input[type="file"],
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #000000;
            border-radius: 5px;
            font-size: 1rem;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #fff;
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

        .info-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
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
            <h1 style="text-align:center; margin-bottom: 25px;">Edit User #{{ $user->id }}</h1>

            <div class="user-info">
                <strong>User ID:</strong> {{ $user->id }}<br>
                <strong>Created:</strong> {{ $user->created_at->format('d-m-Y H:i A') }}<br>
                <div style="margin-top: 10px;">
                    <strong>Current Profile Image:</strong><br>
                    <img src="/drive-in-laravel/uploads/{{ preg_replace('/[^a-zA-Z0-9_]/', '', $user->username) }}/profile.jpg" alt="Profile" style="width: 100px; height: 100px; object-fit: cover; border: 2px solid #000; margin-top: 5px;" onerror="this.style.display='none'">
                </div>
            </div>

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

            <form method="POST" action="{{ route('admin.edit-user.post', $user->id) }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="username">Full Name *</label>
                    <input type="text" id="username" name="username" placeholder="Minimum 6 characters" 
                        minlength="6" required value="{{ old('username', $user->username) }}">
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="pending" {{ old('status', $user->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status', $user->status) == 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="teacher" {{ old('role', $user->role) == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password">Password (Leave blank to keep current)</label>
                    <input type="password" id="password" name="password" placeholder="Minimum 4 characters" minlength="4">
                    <div class="info-text">Leave empty to keep the current password</div>
                </div>

                <div class="form-group">
                    <label for="profileImage">Profile Image (Optional)</label>
                    <input type="file" id="profileImage" name="profileImage" accept="image/*">
                    <div class="info-text">Upload a new profile image (max 5MB)</div>
                </div>

                <div class="button-group">
                    <button type="submit" class="btn-submit">Update User</button>
                    <a href="{{ route('admin.users') }}" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>

@endsection
