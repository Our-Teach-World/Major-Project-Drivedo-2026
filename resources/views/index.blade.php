<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduShare - Teacher and Student File Sharing Platform</title>
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

        /* Navbar */
        header.navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #000000;
            padding: 15px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
        }

        .navbar-container img {
            height: 40px;
            width: auto;
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
            border-bottom: 2px solid transparent;
            padding-bottom: 5px;
            transition: border 0.3s;
        }

        .navbar-menu a:hover,
        .navbar-menu a.active {
            border-bottom: 2px solid #000000;
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
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background-color: #000000;
            color: #ffffff;
        }

        .btn-primary {
            background-color: #000000;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #ffffff;
            color: #000000;
        }

        /* Hero Section */
        .hero {
            background-color: #f5f5f5;
            border-bottom: 2px solid #000000;
            padding: 100px 20px;
            text-align: center;
        }

        .hero-title {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 20px;
            color: #000000;
        }

        .hero-description {
            font-size: 1.2rem;
            margin-bottom: 30px;
            color: #333333;
            max-width: 800px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-actions {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-lg {
            padding: 15px 30px;
            font-size: 1rem;
        }

        /* Features Section */
        .features {
            padding: 60px 20px;
            background-color: #ffffff;
        }

        .features-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .features-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #000000;
        }

        .features-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background-color: #f9f9f9;
            border: 2px solid #000000;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
            transition: transform 0.3s;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.3);
        }

        .feature-icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .feature-card h3 {
            font-size: 1.3rem;
            margin-bottom: 10px;
            color: #000000;
        }

        .feature-card p {
            color: #555555;
            line-height: 1.6;
        }

        /* Footer */
        footer {
            background-color: #000000;
            color: #ffffff;
            text-align: center;
            padding: 20px;
            border-top: 2px solid #000000;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        @media (max-width: 768px) {
            .navbar-menu {
                display: none;
            }

            .hero-title {
                font-size: 2rem;
            }

            .hero-description {
                font-size: 1rem;
            }

            .features-title {
                font-size: 1.8rem;
            }

            .navbar-menu-mobile {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="navbar-container">
            <img src="https://via.placeholder.com/40x40/000/fff?text=Logo" alt="EduShare Logo">

            <nav class="navbar-menu">
                <ul>
                    <li><a href="/" class="active">Home</a></li>
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
        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <h1 class="hero-title">Secure File Sharing for Education</h1>
                <p class="hero-description">
                    Connect teachers and students with a secure platform designed to make sharing educational resources simple and efficient.
                </p>
                <div class="hero-actions">
                    <a href="/register" class="btn btn-primary btn-lg">Get Started</a>
                    <a href="/about" class="btn btn-lg">Learn More</a>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section class="features">
            <div class="container">
                <div class="features-header">
                    <h2 class="features-title">Powerful Features</h2>
                </div>
                <div class="features-grid">
                    <div class="feature-card">
                        <div class="feature-icon">📁</div>
                        <h3>Easy File Sharing</h3>
                        <p>Teachers can upload and organize their educational materials in one place.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">🔐</div>
                        <h3>Secure Access</h3>
                        <p>Role-based access control ensures only authorized users see what they should.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">💬</div>
                        <h3>AI Assistant</h3>
                        <p>Built-in chatbot powered by AI to help students find answers and learn better.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">📊</div>
                        <h3>Organized Files</h3>
                        <p>Automatic categorization of documents, images, audio, and video files.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">⚡</div>
                        <h3>Fast & Reliable</h3>
                        <p>Quick file downloads and reliable storage for all your educational content.</p>
                    </div>
                    <div class="feature-card">
                        <div class="feature-icon">👥</div>
                        <h3>User Management</h3>
                        <p>Admin panel to manage users, approve accounts, and oversee the platform.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 EduShare. All rights reserved.</p>
    </footer>
</body>
</html>
