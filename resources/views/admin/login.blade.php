<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CampusCore</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .login-container {
            background-color: #ffffff;
            border: 2px solid #000000;
            box-shadow: 5px 5px 0px rgba(0, 0, 0, 0.2);
            border-radius: 10px;
            max-width: 400px;
            width: 100%;
            padding: 40px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
            color: #000000;
            font-size: 1.8rem;
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

        input {
            width: 100%;
            padding: 12px;
            border: 2px solid #000000;
            border-radius: 5px;
            font-size: 1rem;
            background-color: #ffffff;
            color: #000000;
            font-family: inherit;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            border-radius: 5px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .alert {
            background-color: #ffebee;
            border: 2px solid #c62828;
            color: #c62828;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Admin Login</h2>

        @if ($errors->any())
            <div class="alert">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}">
            @csrf
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required value="{{ old('username') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
