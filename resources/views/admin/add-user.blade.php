<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New User - EduShare Admin</title>
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
        <div class="navbar-title">⚙️ Add New User</div>
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
            <h1>Create New User</h1>

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

            <form method="POST" action="{{ route('admin.add-user.post') }}">
                @csrf

                <div class="form-group">
                    <label for="username">Full Name *</label>
                    <input type="text" id="username" name="username" placeholder="Minimum 6 characters" 
                        minlength="6" required value="{{ old('username') }}">
                </div>

                <div class="form-group">
                    <label for="role">Role *</label>
                    <select id="role" name="role" required>
                        <option value="">-- Select Role --</option>
                        <option value="teacher" {{ old('role') == 'teacher' ? 'selected' : '' }}>Teacher</option>
                        <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
                    </select>
                </div>

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
</body>
</html>
