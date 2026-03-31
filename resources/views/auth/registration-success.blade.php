<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - EduShare</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #e8f5e9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .success-container {
            background-color: #ffffff;
            border: 2px solid #2e7d32;
            box-shadow: 5px 5px 0px rgba(46, 125, 50, 0.2);
            border-radius: 10px;
            max-width: 500px;
            width: 100%;
            padding: 40px;
            text-align: center;
        }

        .success-icon {
            font-size: 4rem;
            margin-bottom: 20px;
        }

        h2 {
            color: #2e7d32;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        p {
            color: #333333;
            line-height: 1.6;
            margin-bottom: 15px;
            font-size: 1rem;
        }

        .info-box {
            background-color: #f5f5f5;
            border: 2px solid #2e7d32;
            padding: 20px;
            border-radius: 5px;
            margin: 20px 0;
            text-align: left;
        }

        .info-box strong {
            color: #2e7d32;
        }

        .links {
            margin-top: 30px;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2e7d32;
            color: #ffffff;
            border: 2px solid #2e7d32;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            transition: 0.3s;
            margin: 5px;
        }

        .btn:hover {
            background-color: #ffffff;
            color: #2e7d32;
        }
    </style>
</head>
<body>
    <div class="success-container">
        <div class="success-icon">✓</div>
        <h2>Registration Successful!</h2>
        <p>Your account has been created successfully.</p>

        <div class="info-box">
            <p><strong>Note:</strong> If you registered as a <strong>Teacher</strong>, your account is currently <strong>pending approval</strong>. An administrator will review your registration and approve it soon. You will be notified when your account is approved.</p>
            <p><strong>Students</strong> can log in immediately.</p>
        </div>

        <p>Thank you for joining EduShare!</p>

        <div class="links">
            <a href="{{ route('login') }}" class="btn">Go to Login</a>
            <a href="/" class="btn" style="background-color: #ffffff; color: #2e7d32; border: 2px solid #2e7d32;">Back to Home</a>
        </div>
    </div>
</body>
</html>
