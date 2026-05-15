<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Successful - CampusCore</title>
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

        .success-container {
            background-color: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            box-shadow: 0 10px 50px rgba(6, 20, 27, 0.1);
            border-radius: 24px;
            max-width: 520px;
            width: 100%;
            padding: 50px 40px;
            text-align: center;
        }

        .success-icon {
            font-size: 4.5rem;
            margin-bottom: 25px;
            color: #253745;
        }

        h2 {
            color: #06141B;
            font-size: 2.2rem;
            margin-bottom: 20px;
            font-weight: 800;
            letter-spacing: -1px;
        }

        p {
            color: #4A5568;
            line-height: 1.7;
            margin-bottom: 15px;
            font-size: 1.05rem;
        }

        .info-box {
            background-color: #F8F9F9;
            border: 1px solid rgba(37, 55, 69, 0.1);
            padding: 25px;
            border-radius: 16px;
            margin: 30px 0;
            text-align: left;
        }

        .info-box strong {
            color: #253745;
            font-weight: 800;
        }

        .links {
            margin-top: 35px;
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            display: inline-block;
            padding: 14px 28px;
            background-color: #253745;
            color: #CCD0CF;
            border: none;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 800;
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(37, 55, 69, 0.15);
            font-size: 1rem;
        }

        .btn:hover {
            background-color: #1a2833;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(37, 55, 69, 0.25);
        }

        .btn-outline {
            background-color: #F8F9F9;
            color: #253745;
            border: 1px solid rgba(37, 55, 69, 0.1);
            box-shadow: none;
        }

        .btn-outline:hover {
            background-color: #E2E8F0;
            transform: translateY(-2px);
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

        <p>Thank you for joining CampusCore!</p>

        <div class="links">
            <a href="{{ route('login') }}" class="btn">Go to Login</a>
            <a href="/" class="btn btn-outline">Back to Home</a>
        </div>
    </div>
</body>
</html>
