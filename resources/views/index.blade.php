<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusCore | Architectural Academic Intelligence</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-base: #CCD0CF;
            --text-main: #06141B;
            --primary: #253745;
            --accent-indigo: #4F46E5;
            --accent-emerald: #10B981;
            --accent-amber: #F59E0B;
            --glass-white: rgba(255, 255, 255, 0.45);
            --glass-border: rgba(255, 255, 255, 0.4);
            --transition: all 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Space Grotesk', sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-main);
            overflow-x: hidden;
            line-height: 1.5;
            -webkit-font-smoothing: antialiased;
        }

        /* Architectural Mesh Background */
        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: 
                radial-gradient(circle at 2px 2px, rgba(6, 20, 27, 0.05) 1px, transparent 0);
            background-size: 40px 40px;
            z-index: -1;
            opacity: 0.5;
        }

        /* Typography Masterclass */
        h1, h2, h3 { font-weight: 700; letter-spacing: -0.06em; }
        p { font-family: 'Inter', sans-serif; font-weight: 400; color: rgba(6, 20, 27, 0.8); }

        .display-xl {
            font-size: clamp(80px, 15vw, 160px);
            line-height: 0.8;
            text-transform: uppercase;
            letter-spacing: -0.08em;
        }

        /* Nav System */
        .nav-fixed {
            position: fixed;
            top: 0; width: 100%;
            padding: 32px 80px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            transition: var(--transition);
        }

        .nav-fixed.scrolled {
            padding: 20px 80px;
            backdrop-filter: blur(20px);
            background: rgba(204, 208, 207, 0.8);
            border-bottom: 1px solid var(--glass-border);
        }

        .logo {
            font-size: 32px;
            font-weight: 800;
            color: var(--primary);
            text-decoration: none;
            letter-spacing: -0.05em;
        }

        .nav-links { display: flex; gap: 48px; }
        .nav-links a {
            text-decoration: none;
            color: var(--text-main);
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.2em;
            transition: var(--transition);
            opacity: 0.6;
        }
        .nav-links a:hover { opacity: 1; color: var(--accent-indigo); }

        /* Button Architecture */
        .btn {
            padding: 18px 48px;
            border-radius: 0px; /* Sharp corners for absolute precision */
            font-weight: 700;
            text-decoration: none;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-size: 11px;
            transition: var(--transition);
            cursor: pointer;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: var(--primary);
            color: var(--bg-base);
            border: 1px solid var(--primary);
        }

        .btn-primary:hover {
            background: transparent;
            color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 30px 60px rgba(37, 55, 69, 0.15);
        }

        .btn-glass {
            background: transparent;
            color: var(--text-main);
            border: 1px solid var(--text-main);
        }

        .btn-glass:hover {
            background: var(--text-main);
            color: var(--bg-base);
            transform: translateY(-5px);
        }

        /* Hero: The Statement */
        .hero {
            height: 100vh;
            display: flex;
            align-items: center;
            padding: 0 80px;
            position: relative;
            background: var(--bg-base);
        }

        .hero-content {
            z-index: 10;
            max-width: 1200px;
        }

        .hero-tag {
            font-size: 14px;
            font-weight: 800;
            letter-spacing: 0.5em;
            text-transform: uppercase;
            color: var(--accent-indigo);
            margin-bottom: 40px;
            display: block;
        }

        .hero-title {
            margin-bottom: 60px;
            color: var(--text-main);
        }

        .hero-subtitle {
            font-size: 22px;
            max-width: 650px;
            margin-bottom: 80px;
            line-height: 1.4;
            opacity: 0.9;
        }

        /* Background Graphics - CLEAN */
        .hero-backdrop {
            position: absolute;
            top: 0; right: 0;
            width: 50%; height: 100%;
            background: url('/images/hero_clean.png') no-repeat center center/cover;
            mask-image: linear-gradient(to left, black 50%, transparent 100%);
            opacity: 0.9;
            z-index: 1;
        }

        /* Sections */
        .section {
            padding: 180px 80px;
            position: relative;
        }

        .container { max-width: 1440px; margin: 0 auto; }

        /* Feature Tiers */
        .tier-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 120px;
            align-items: center;
            margin-bottom: 180px;
        }

        .tier-content h2 {
            font-size: 72px;
            margin-bottom: 32px;
            color: var(--primary);
        }

        .tier-content p {
            font-size: 20px;
            margin-bottom: 48px;
            max-width: 500px;
        }

        /* The Glass Block */
        .glass-block {
            background: var(--glass-white);
            backdrop-filter: blur(40px);
            border: 1px solid var(--glass-border);
            padding: 80px;
            position: relative;
            transition: var(--transition);
        }

        .glass-block::before {
            content: "";
            position: absolute;
            top: -1px; left: -1px; right: -1px; bottom: -1px;
            border: 1px solid rgba(255,255,255,0.8);
            z-index: -1;
            pointer-events: none;
        }

        .glass-block:hover {
            transform: scale(1.03) rotate(1deg);
            background: rgba(255,255,255,0.6);
            box-shadow: 0 80px 120px rgba(0,0,0,0.08);
        }

        .block-tag {
            font-size: 12px;
            font-weight: 800;
            color: var(--accent-indigo);
            letter-spacing: 0.3em;
            text-transform: uppercase;
            margin-bottom: 24px;
            display: block;
        }

        .block-title { font-size: 40px; margin-bottom: 20px; }

        /* Dark Integrity Layer */
        .dark-layer {
            background: var(--primary);
            color: var(--bg-base);
            padding: 180px 80px;
            margin: 0 40px;
            border-radius: 2px;
        }

        .integrity-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            margin-top: 100px;
        }

        .integrity-card {
            border-left: 1px solid rgba(255,255,255,0.1);
            padding-left: 32px;
            transition: var(--transition);
        }

        .integrity-card:hover { border-left: 4px solid var(--accent-emerald); padding-left: 40px; }
        .integrity-card h4 { font-size: 14px; letter-spacing: 0.2em; text-transform: uppercase; margin-bottom: 12px; opacity: 0.5; }
        .integrity-card p { color: var(--bg-base); font-size: 18px; opacity: 1; }

        /* Footer Engineering */
        footer {
            padding: 140px 80px 80px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 80px;
        }

        .footer-logo { font-size: 32px; font-weight: 800; margin-bottom: 40px; display: block; text-decoration: none; color: inherit; }
        .footer-col h5 { margin-bottom: 32px; font-size: 12px; letter-spacing: 0.2em; text-transform: uppercase; opacity: 0.4; }
        .footer-col ul { list-style: none; }
        .footer-col li { margin-bottom: 20px; }
        .footer-col a { text-decoration: none; color: inherit; opacity: 0.7; transition: var(--transition); font-size: 15px; }
        .footer-col a:hover { opacity: 1; color: var(--accent-indigo); padding-left: 10px; }

        /* Mobile Flow */
        @media (max-width: 1024px) {
            .hero { padding: 0 40px; }
            .tier-grid { grid-template-columns: 1fr; gap: 80px; }
            .hero-backdrop { display: none; }
            .display-xl { font-size: 72px; }
            .integrity-grid { grid-template-columns: 1fr 1fr; }
            footer { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .nav-links { display: none; }
            .section { padding: 120px 24px; }
            .hero { padding: 0 24px; }
            .dark-layer { margin: 0 0; padding: 120px 24px; }
            .integrity-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <nav id="navbar" class="nav-fixed">
        <a href="/" class="logo">CAMPUSCORE.</a>
        <div class="nav-links">
            <a href="#intelligence">Intelligence</a>
            <a href="#connectivity">Connectivity</a>
            <a href="#integrity">Integrity</a>
            <a href="/marketplace">Exchange</a>
        </div>
        <div class="nav-cta">
            <a href="/register" class="btn btn-primary">Join the Era</a>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="hero-backdrop"></div>
            <div class="hero-content">
                <span class="hero-tag">Academic Precision System</span>
                <h1 class="hero-title display-xl">Architectural<br>Intelligence.</h1>
                <p class="hero-subtitle">The first professional-grade ecosystem for advanced document synthesis, blockchain verification, and elite mentorship.</p>
                <div class="hero-actions">
                    <a href="/register" class="btn btn-primary">Enter Now</a>
                    <a href="#intelligence" class="btn btn-glass" style="margin-left: 24px;">View Technicals</a>
                </div>
            </div>
        </section>

        <section id="intelligence" class="section">
            <div class="container">
                <div class="tier-grid">
                    <div class="tier-content">
                        <span class="hero-tag">01. Cognitive layer</span>
                        <h2>AI Document Synthesis.</h2>
                        <p>Harnessing NVIDIA-powered RAG technology to transform static academic archives into interactive knowledge graphs.</p>
                        <a href="/register" class="btn btn-glass">Explore AI Engine</a>
                    </div>
                    <div class="glass-block">
                        <span class="block-tag">Smart Retrieval</span>
                        <h3 class="block-title">Deep Indexing</h3>
                        <p>Our AI doesn't just search; it understands relationships. Instant contextual retrieval from thousands of academic papers.</p>
                    </div>
                </div>

                <div class="tier-grid" style="direction: rtl; margin-bottom: 0;">
                    <div class="tier-content" style="direction: ltr;">
                        <span class="hero-tag">02. Value exchange</span>
                        <h2>Professional Connectivity.</h2>
                        <p>A unified hub for career opportunities and the revolutionary 'Book Loop'—our circular economy for academic resources.</p>
                        <a href="/marketplace" class="btn btn-glass">Access Exchange</a>
                    </div>
                    <div class="glass-block" style="direction: ltr;">
                        <span class="block-tag">Expert Tunnel</span>
                        <h3 class="block-title">Elite Mentorship</h3>
                        <p>Direct secure tunnels to industry alumni. Get verified guidance from those who have already engineered the path.</p>
                    </div>
                </div>
            </div>
        </section>

        <section id="integrity" class="dark-layer">
            <div class="container">
                <span class="hero-tag" style="color: var(--accent-emerald);">03. Integrity layer</span>
                <h2 class="display-xl" style="color: var(--bg-base); font-size: clamp(48px, 10vw, 96px);">Immutable<br>Verification.</h2>
                
                <div class="integrity-grid">
                    <div class="integrity-card">
                        <h4>Blockchain</h4>
                        <p>CertChain Verified Credentials</p>
                    </div>
                    <div class="integrity-card">
                        <h4>Encryption</h4>
                        <p>End-to-End Secure Protocols</p>
                    </div>
                    <div class="integrity-card">
                        <h4>governance</h4>
                        <p>Smart Academic Contracts</p>
                    </div>
                    <div class="integrity-card">
                        <h4>Assets</h4>
                        <p>Intellectual Tokenization</p>
                    </div>
                </div>
            </div>
        </section>

        <section class="section" style="text-align: center;">
            <div class="container">
                <span class="hero-tag">Finalization</span>
                <h2 class="display-xl" style="margin-bottom: 80px; color: var(--primary);">Secure Your<br>Legacy.</h2>
                <a href="/register" class="btn btn-primary" style="padding: 24px 80px; font-size: 16px;">Initialize Registration</a>
            </div>
        </section>
    </main>

    <footer>
        <div class="footer-col">
            <a href="/" class="footer-logo">CAMPUSCORE.</a>
            <p style="opacity: 0.6; max-width: 300px;">Setting the global standard for architectural academic intelligence and document management.</p>
        </div>
        <div class="footer-col">
            <h5>Systems</h5>
            <ul>
                <li><a href="/marketplace">Exchange Hub</a></li>
                <li><a href="/student/mentorship">Alumni Network</a></li>
                <li><a href="/quiz">AI Evaluation</a></li>
                <li><a href="/certchain">CertChain</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Technical</h5>
            <ul>
                <li><a href="#">Security Protocols</a></li>
                <li><a href="#">API Documentation</a></li>
                <li><a href="#">Whitepaper</a></li>
                <li><a href="#">Network Status</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h5>Social</h5>
            <ul>
                <li><a href="#">LinkedIn</a></li>
                <li><a href="#">Twitter</a></li>
                <li><a href="#">GitHub</a></li>
            </ul>
        </div>
    </footer>

    <script>
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 100) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    </script>
</body>
</html>
