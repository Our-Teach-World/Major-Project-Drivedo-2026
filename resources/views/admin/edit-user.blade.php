<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User - EduShare Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #000000;
        }

        .navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #000000;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-title {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .navbar-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 8px 16px;
            border: 2px solid #000000;
            background-color: #ffffff;
            color: #000000;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #000000;
            color: #ffffff;
        }

        .container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px 20px;
        }

        .form-card {
            background-color: #ffffff;
            border: 2px solid #000000;
            padding: 30px;
            border-radius: 8px;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 25px;
            text-align: center;
        }

        .user-info {
            background-color: #f9f9f9;
            border: 1px solid #e0e0e0;
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

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 2px solid;
        }

        .alert-error {
            background-color: #ffebee;
            border-color: #c62828;
            color: #c62828;
        }

        .error-list {
            margin-top: 10px;
        }

        .error-list li {
            margin-left: 20px;
        }

        .info-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 5px;
        }

        @media (max-width: 768px) {
            .form-card {
                padding: 20px;
            }

            h1 {
                font-size: 1.5rem;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">⚙️ Edit User</div>
        <div class="navbar-actions">
            <a href="{{ route('admin.users') }}" class="btn">Back to Users</a>
            <form method="POST" action="{{ route('admin.logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="form-card">
            <h1>Edit User #{{ $user->id }}</h1>

            <div class="user-info">
                <strong>User ID:</strong> {{ $user->id }}<br>
                <strong>Created:</strong> {{ $user->created_at->format('d-m-Y H:i A') }}<br>
                <strong>Current Profile Image:</strong><br>
                <img src="/drive-in-laravel/uploads/{{ preg_replace('/[^a-zA-Z0-9_]/', '', $user->username) }}/profile.jpg" alt="Profile" style="width: 100px; height: 100px; object-fit: cover; border: 1px solid #000;" onerror="this.style.display='none'">
            </div>

            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Please fix the following errors:</strong>
                    <ul class="error-list">
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
</body>
</html>
