<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About - CampusCore</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #ffffff;
            color: #000000;
        }

        header.navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #000000;
            padding: 15px 0;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .navbar-menu ul {
            list-style: none;
            display: flex;
            gap: 30px;
        }

        .navbar-menu a {
            text-decoration: none;
            color: #000000;
            font-weight: 500;
        }

        .navbar-actions {
            display: flex;
            gap: 15px;
        }

        .btn {
            padding: 10px 20px;
            border: 2px solid #000000;
            background-color: #ffffff;
            color: #000000;
            text-decoration: none;
            font-weight: 600;
            border-radius: 5px;
        }

        .btn-primary {
            background-color: #000000;
            color: #ffffff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.8;
            margin-bottom: 15px;
            color: #333333;
        }

        footer {
            background-color: #000000;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            border-top: 2px solid #000000;
            margin-top: 60px;
        }
    </style>
</head>
<body>
    <header class="navbar">
        <div class="navbar-container">
            <img src="https://via.placeholder.com/40x40/000/fff?text=Logo" alt="CampusCore Logo" style="height: 40px;">
            <nav class="navbar-menu">
                <ul>
                    <li><a href="/">Home</a></li>
                    <li><a href="/about">About</a></li>
                    <li><a href="/contact">Contact</a></li>
                </ul>
            </nav>
            <div class="navbar-actions">
                <a href="/login" class="btn">Login</a>
                <a href="/register" class="btn btn-primary">Register</a>
            </div>
        </div>
    </header>

    <main>
        <div class="container">
            <h1>About CampusCore</h1>
            <p>
                CampusCore is a modern educational platform designed to bridge the gap between teachers and students. 
                Our mission is to make sharing educational resources simple, secure, and efficient.
            </p>
            <p>
                With CampusCore, teachers can easily upload and organize their educational materials, while students 
                can access these resources whenever they need them. Our AI-powered chatbot provides additional learning 
                support, helping students find answers to their questions.
            </p>
            <p>
                Built with security and ease of use in mind, CampusCore provides a reliable solution for educational 
                institutions and independent educators alike.
            </p>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 CampusCore. All rights reserved.</p>
    </footer>
</body>
</html>
