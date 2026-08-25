<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - CampusCore</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: #CCD0CF;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
            color: #06141B;
        }

        .login-container {
            background-color: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            box-shadow: 0 10px 50px rgba(6, 20, 27, 0.1);
            border-radius: 24px;
            max-width: 420px;
            width: 100%;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #06141B;
            font-size: 2rem;
            font-weight: 800;
            letter-spacing: -1px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 700;
            color: #253745;
            font-size: 0.9rem;
        }

        input,
        select {
            width: 100%;
            padding: 14px;
            border: 1px solid rgba(6, 20, 27, 0.1);
            border-radius: 12px;
            font-size: 1rem;
            background-color: #F8F9F9;
            color: #06141B;
            font-family: inherit;
            transition: all 0.2s;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #253745;
            box-shadow: 0 0 0 3px rgba(37, 55, 69, 0.1);
        }

        button {
            width: 100%;
            padding: 14px;
            background-color: #253745;
            color: #CCD0CF;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(37, 55, 69, 0.15);
        }

        button:hover {
            background-color: #1a2833;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(37, 55, 69, 0.25);
        }

        .links {
            text-align: center;
            margin-top: 25px;
        }

        .links p {
            color: #4A5568;
            font-size: 0.95rem;
        }

        .links a {
            color: #253745;
            text-decoration: none;
            font-weight: 700;
        }

        .links a:hover {
            text-decoration: underline;
        }

        .alert {
            background-color: #FEF2F2;
            border: 1px solid #FECACA;
            color: #991B1B;
            padding: 15px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 600;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required value="{{ old('username') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" required>
                    <option value="">Select Role</option>
                    <option value="student">Student</option>
                    <option value="teacher">Teacher</option>
                    <option value="alumni">Alumni</option>
                </select>
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="links">
            <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>
</body>

</html>