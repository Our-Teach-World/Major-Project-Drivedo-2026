<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CampusCore | The Ultimate Academic Ecosystem</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;700;900&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- GSAP for best-in-class animations -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollTrigger.min.js"></script>

    <style>
        :root {
            --bg-base: #FAFAFA;
            --bg-alt: #FFFFFF;
            --text-primary: #0F172A;
            --text-muted: #64748B;
            --accent-glow: #4F46E5;
            --accent-secondary: #0EA5E9;
            --glass-bg: rgba(255, 255, 255, 0.7);
            --glass-border: rgba(15, 23, 42, 0.08);
            --card-bg: #FFFFFF;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            cursor: none; /* Hide default cursor for custom one */
        }

        body {
            background-color: var(--bg-base);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .display-text {
            font-family: 'Outfit', sans-serif;
        }

        /* Custom Cursor */
        .cursor-dot, .cursor-outline {
            position: fixed;
            top: 0; left: 0;
            transform: translate(-50%, -50%);
            border-radius: 50%;
            z-index: 9999;
            pointer-events: none;
        }
        .cursor-dot {
            width: 8px; height: 8px;
            background-color: var(--accent-glow);
        }
        .cursor-outline {
            width: 40px; height: 40px;
            border: 1px solid rgba(79, 70, 229, 0.5);
            transition: width 0.2s, height 0.2s;
        }

        /* Dynamic Background Mesh */
        .bg-mesh {
            position: fixed;
            top: 0; left: 0; width: 100vw; height: 100vh;
            background-image: 
                radial-gradient(circle at 15% 50%, rgba(79, 70, 229, 0.05), transparent 25%),
                radial-gradient(circle at 85% 30%, rgba(14, 165, 233, 0.05), transparent 25%);
            z-index: -1;
            pointer-events: none;
        }

        /* Nav - Fixed & Readable Glassmorphism */
        nav {
            position: fixed;
            top: 0; left: 0; width: 100%;
            padding: 24px 5vw;
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03);
            transition: all 0.3s ease;
        }
        .nav-scrolled {
            padding: 16px 5vw;
        }
        .logo {
            font-size: 24px; font-weight: 900; letter-spacing: -1px; text-transform: uppercase;
            color: var(--text-primary);
        }
        .nav-links { display: flex; gap: 40px; }
        .nav-links a {
            color: var(--text-primary); text-decoration: none; font-size: 13px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 2px;
            position: relative; opacity: 0.8;
        }
        .nav-links a:hover { opacity: 1; }
        .nav-links a::after {
            content: ''; position: absolute; bottom: -5px; left: 0; width: 0%; height: 2px;
            background: var(--accent-glow); transition: width 0.3s ease;
        }
        .nav-links a:hover::after { width: 100%; }

        /* Buttons */
        .btn {
            display: inline-block; padding: 16px 40px;
            border-radius: 100px; font-size: 14px; font-weight: 600;
            text-transform: uppercase; letter-spacing: 1px;
            text-decoration: none; transition: all 0.3s ease;
            position: relative; overflow: hidden;
            border: 2px solid transparent;
        }
        .btn-primary {
            background: var(--text-primary); color: white;
        }
        .btn-primary:hover {
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.2); transform: translateY(-2px);
        }
        .btn-glow {
            background: transparent; border-color: var(--text-primary); color: var(--text-primary);
        }
        .btn-glow:hover {
            background: var(--text-primary); color: white;
            box-shadow: 0 10px 30px rgba(15, 23, 42, 0.1); transform: translateY(-2px);
        }

        /* Hero */
        .hero {
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            padding: 120px 5vw 0; position: relative; overflow: hidden;
        }
        .hero-content {
            text-align: center; z-index: 10; max-width: 1200px;
        }
        .huge-text {
            font-size: clamp(60px, 12vw, 200px);
            font-weight: 900; line-height: 0.85;
            letter-spacing: -0.05em; text-transform: uppercase;
            background: linear-gradient(to bottom right, var(--text-primary), #64748B);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
        }
        .hero-sub {
            font-size: clamp(18px, 2vw, 24px); color: var(--text-muted);
            max-width: 700px; margin: 0 auto 50px; font-weight: 400; line-height: 1.6;
        }

        /* Impact Section - The Hard Work */
        .impact-section {
            padding: 120px 5vw;
            background: var(--text-primary);
            color: white;
            border-radius: 40px;
            margin: 0 2vw 150px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .impact-section::before {
            content: '';
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: radial-gradient(circle at 50% -20%, rgba(79, 70, 229, 0.3), transparent 70%);
        }
        .impact-title {
            font-size: clamp(40px, 6vw, 80px);
            font-weight: 900; margin-bottom: 40px; line-height: 1;
            position: relative; z-index: 2;
        }
        .impact-text {
            font-size: 20px; color: rgba(255,255,255,0.8);
            max-width: 800px; margin: 0 auto 60px; line-height: 1.8;
            position: relative; z-index: 2;
        }
        .stats-grid {
            display: grid; grid-template-columns: repeat(3, 1fr); gap: 40px;
            position: relative; z-index: 2; border-top: 1px solid rgba(255,255,255,0.1);
            padding-top: 60px;
        }
        .stat-item h4 { font-size: 64px; font-weight: 900; color: var(--accent-glow); margin-bottom: 10px; line-height: 1; }
        .stat-item p { font-size: 16px; font-weight: 500; text-transform: uppercase; letter-spacing: 1px; color: rgba(255,255,255,0.6); }

        /* Sections */
        .section {
            padding: 150px 5vw; position: relative;
        }
        .section-title {
            font-size: clamp(40px, 8vw, 120px); font-weight: 900;
            line-height: 0.9; margin-bottom: 100px;
            color: var(--text-primary);
            text-transform: uppercase;
            letter-spacing: -0.03em;
        }

        /* Features Layout */
        .feature-grid {
            display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center;
            margin-bottom: 150px;
        }
        .feature-grid:nth-child(even) { direction: rtl; }
        .feature-grid:nth-child(even) > * { direction: ltr; }

        .feature-text h3 {
            font-size: clamp(30px, 4vw, 56px); margin-bottom: 24px; line-height: 1.1; color: var(--text-primary);
        }
        .feature-text p {
            font-size: 18px; color: var(--text-muted); margin-bottom: 40px; line-height: 1.7; max-width: 500px;
        }

        .glass-card {
            background: var(--card-bg); 
            border: 1px solid var(--glass-border); border-radius: 24px;
            padding: 60px; position: relative; overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.04);
            transform-style: preserve-3d; perspective: 1000px;
        }
        .glass-card::before {
            content: ''; position: absolute; top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.8), transparent);
            transform: skewX(-20deg); transition: 0.5s; z-index: 1;
        }
        .glass-card:hover::before { left: 150%; }

        .card-icon {
            font-size: 40px; margin-bottom: 30px; display: inline-block;
            padding: 20px; border-radius: 16px; background: rgba(79, 70, 229, 0.05);
            position: relative; z-index: 2;
        }
        .glass-card h4, .glass-card p { position: relative; z-index: 2; }
        .glass-card h4 { color: var(--text-primary); font-size: 24px; margin-bottom: 16px; }

        /* Footer */
        footer {
            padding: 100px 5vw; background: var(--bg-alt); border-top: 1px solid var(--glass-border);
            display: grid; grid-template-columns: 2fr 1fr 1fr; gap: 60px;
        }
        .footer-logo { font-size: 32px; font-weight: 900; font-family: 'Outfit'; margin-bottom: 20px; color: var(--text-primary);}
        .footer-links h4 { font-size: 14px; text-transform: uppercase; letter-spacing: 2px; color: var(--text-muted); margin-bottom: 24px;}
        .footer-links ul { list-style: none; }
        .footer-links li { margin-bottom: 16px; }
        .footer-links a { color: var(--text-primary); text-decoration: none; font-size: 16px; transition: color 0.3s; font-weight: 500;}
        .footer-links a:hover { color: var(--accent-glow); }

        @media (max-width: 1024px) {
            .feature-grid { grid-template-columns: 1fr; gap: 40px; }
            .feature-grid:nth-child(even) { direction: ltr; }
            footer { grid-template-columns: 1fr; }
            .nav-links { display: none; }
            .stats-grid { grid-template-columns: 1fr; gap: 60px; }
            .impact-section { margin: 0 0 100px; border-radius: 0; }
        }
    </style>
</head>
<body>

    <!-- Custom Cursor -->
    <div class="cursor-dot"></div>
    <div class="cursor-outline"></div>

    <div class="bg-mesh"></div>

    <nav id="navbar">
        <div class="logo">CAMPUSCORE</div>
        <div class="nav-links">
            <a href="#impact" class="hover-target">Impact</a>
            <a href="#ecosystem" class="hover-target">Ecosystem</a>
            <a href="#academic" class="hover-target">Academic</a>
            <a href="#exchange" class="hover-target">Exchange</a>
        </div>
        <div>
            <a href="/login" class="btn btn-primary hover-target">System Login</a>
        </div>
    </nav>

    <main>
        <section class="hero">
            <div class="hero-content">
                <div class="huge-text gs-reveal">Academic</div>
                <div class="huge-text gs-reveal" style="color: transparent; -webkit-text-stroke: 2px var(--text-primary);">Evolution.</div>
                <p class="hero-sub gs-reveal">A high-performance ecosystem integrating elite mentorship, precision management, and peer-to-peer exchange into one unified architectural platform.</p>
                <div class="gs-reveal" style="margin-top: 40px;">
                    <a href="/register" class="btn btn-primary hover-target" style="margin-right: 20px;">Initialize Profile</a>
                    <a href="#impact" class="btn btn-glow hover-target">Discover the Impact</a>
                </div>
            </div>
        </section>

        <!-- New Impact & Hard Work Section -->
        <section id="impact" class="impact-section gs-fade">
            <h2 class="impact-title">A Monumental Shift.</h2>
            <p class="impact-text">Building CampusCore wasn't just about writing code; it was an ambitious engineering undertaking to solve genuine campus friction. Hundreds of hours were invested into architecting 5 completely disparate systems—from real-time chat and automated attendance generation to a peer-to-peer marketplace—and synthesizing them into a single, cohesive, premium experience that benefits students, alumni, and administration equally.</p>
            
            <div class="stats-grid">
                <div class="stat-item gs-reveal">
                    <h4>5+</h4>
                    <p>Integrated Modules</p>
                </div>
                <div class="stat-item gs-reveal">
                    <h4>Zero</h4>
                    <p>Administrative Friction</p>
                </div>
                <div class="stat-item gs-reveal">
                    <h4>100%</h4>
                    <p>Ecosystem Synergy</p>
                </div>
            </div>
        </section>

        <section id="ecosystem" class="section">
            <h2 class="section-title gs-slide">The Ecosystem</h2>
            
            <div class="feature-grid">
                <div class="feature-text gs-fade">
                    <h3>Elite Alumni Mentorship</h3>
                    <p>Bypass the noise. Establish secure, direct tunnels with verified alumni who have already engineered the path you are on. Request guidance and join dedicated 1-on-1 session chats.</p>
                    <a href="/register" class="btn btn-primary hover-target">Find a Mentor</a>
                </div>
                <div class="glass-card gs-fade hover-target">
                    <span class="card-icon">🧠</span>
                    <h4>Knowledge Transfer Protocol</h4>
                    <p style="color: var(--text-muted); line-height: 1.6;">Browse alumni by industry branch, submit targeted mentorship requests, and unlock private session channels for high-value advice.</p>
                </div>
            </div>

            <div class="feature-grid" id="academic">
                <div class="feature-text gs-fade">
                    <h3>Precision Academic Management</h3>
                    <p>A zero-friction administrative layer. Real-time dynamic timetables, hierarchical notice boards directly from the Principal, and instant bulk attendance processing.</p>
                </div>
                <div class="glass-card gs-fade hover-target">
                    <span class="card-icon">⚡</span>
                    <h4>Workflow Automation</h4>
                    <p style="color: var(--text-muted); line-height: 1.6;">Faculty can instantly mark bulk attendance, generate Excel reports, and broadcast critical updates to segmented student bodies instantly.</p>
                </div>
            </div>
            
            <div class="feature-grid">
                <div class="feature-text gs-fade">
                    <h3>AI-Powered Assessment</h3>
                    <p>Elevating the testing paradigm. Faculty deploy complex quizzes while students track their performance metrics in real-time through an advanced analytical dashboard.</p>
                </div>
                <div class="glass-card gs-fade hover-target">
                    <span class="card-icon">🎯</span>
                    <h4>Cognitive Evaluation</h4>
                    <p style="color: var(--text-muted); line-height: 1.6;">Dynamic question rendering, instant scoring, and comprehensive post-assessment breakdowns for continuous academic improvement.</p>
                </div>
            </div>
        </section>

        <section id="exchange" class="section" style="background: var(--bg-alt);">
            <h2 class="section-title gs-slide">Value Exchange</h2>
            
            <div class="feature-grid">
                <div class="feature-text gs-fade">
                    <h3>The Student Marketplace</h3>
                    <p>A closed-loop economy for the campus. CS and Electronics branches get exclusive access to internship drops and collaborative project listings. </p>
                    <a href="/marketplace" class="btn btn-primary hover-target">Access Market</a>
                </div>
                <div class="glass-card gs-fade hover-target">
                    <span class="card-icon">🔄</span>
                    <h4>BookLoop Protocol</h4>
                    <p style="color: var(--text-muted); line-height: 1.6;">A peer-to-peer exchange for academic resources. List materials, initiate direct buyer-seller negotiations via integrated chat, and recycle knowledge.</p>
                </div>
            </div>

            <div class="feature-grid">
                <div class="feature-text gs-fade">
                    <h3>Secure Resource Hub</h3>
                    <p>Centralized, high-speed document distribution. Faculty can upload and organize study materials, ensuring students have unhindered access to mission-critical files.</p>
                </div>
                <div class="glass-card gs-fade hover-target">
                    <span class="card-icon">📁</span>
                    <h4>Cloud Repository</h4>
                    <p style="color: var(--text-muted); line-height: 1.6;">Direct downloads of lecture notes, PDF guides, and reference architectures instantly synced across all student dashboards.</p>
                </div>
            </div>
        </section>
        
        <section class="section" style="text-align: center; padding: 200px 5vw;">
            <h2 class="huge-text gs-fade" style="font-size: clamp(40px, 8vw, 120px); margin-bottom: 40px;">Initialize Your Future.</h2>
            <p class="gs-fade" style="color: var(--text-muted); font-size: 20px; margin-bottom: 60px;">Join the most advanced academic network.</p>
            <a href="/register" class="btn btn-primary hover-target gs-fade" style="padding: 24px 60px; font-size: 18px;">Create Account</a>
        </section>
    </main>

    <footer>
        <div>
            <div class="footer-logo">CAMPUSCORE.</div>
            <p style="color: var(--text-muted); max-width: 300px; line-height: 1.6;">Engineering the future of academic management and student networking.</p>
        </div>
        <div class="footer-links">
            <h4>Ecosystem</h4>
            <ul>
                <li><a href="/student/mentorship" class="hover-target">Alumni Mentorship</a></li>
                <li><a href="/marketplace" class="hover-target">Marketplace</a></li>
                <li><a href="/books" class="hover-target">BookLoop</a></li>
            </ul>
        </div>
        <div class="footer-links">
            <h4>Access</h4>
            <ul>
                <li><a href="/login" class="hover-target">Student Login</a></li>
                <li><a href="/login" class="hover-target">Faculty Portal</a></li>
                <li><a href="/admin/login" class="hover-target">Admin Console</a></li>
            </ul>
        </div>
    </footer>

    <script>
        // Nav Shrink on Scroll
        window.addEventListener('scroll', () => {
            if(window.scrollY > 50) {
                document.getElementById('navbar').classList.add('nav-scrolled');
            } else {
                document.getElementById('navbar').classList.remove('nav-scrolled');
            }
        });

        // Custom Mouse Cursor
        const dot = document.querySelector('.cursor-dot');
        const outline = document.querySelector('.cursor-outline');
        
        window.addEventListener('mousemove', (e) => {
            const posX = e.clientX;
            const posY = e.clientY;
            
            dot.style.left = `${posX}px`;
            dot.style.top = `${posY}px`;
            
            // Subtle delay for outline
            outline.animate({
                left: `${posX}px`,
                top: `${posY}px`
            }, { duration: 500, fill: "forwards" });
        });

        // Hover effect for cursor
        document.querySelectorAll('.hover-target').forEach(el => {
            el.addEventListener('mouseenter', () => {
                outline.style.transform = 'translate(-50%, -50%) scale(1.5)';
                outline.style.backgroundColor = 'rgba(79, 70, 229, 0.1)';
            });
            el.addEventListener('mouseleave', () => {
                outline.style.transform = 'translate(-50%, -50%) scale(1)';
                outline.style.backgroundColor = 'transparent';
            });
        });

        // GSAP Animations
        gsap.registerPlugin(ScrollTrigger);

        // Hero Reveal
        gsap.from(".gs-reveal", {
            y: 100,
            opacity: 0,
            duration: 1.2,
            stagger: 0.2,
            ease: "power4.out",
            delay: 0.2
        });

        // Mouse Parallax Effect on Hero
        document.addEventListener("mousemove", (e) => {
            const x = (e.clientX / window.innerWidth - 0.5) * 40;
            const y = (e.clientY / window.innerHeight - 0.5) * 40;
            
            gsap.to(".hero-content", {
                x: x,
                y: y,
                duration: 1,
                ease: "power2.out"
            });
        });

        // Scroll Animations for Sections
        gsap.utils.toArray('.gs-slide').forEach(title => {
            gsap.from(title, {
                scrollTrigger: {
                    trigger: title,
                    start: "top 80%",
                },
                x: -100,
                opacity: 0,
                duration: 1.5,
                ease: "power3.out"
            });
        });

        gsap.utils.toArray('.gs-fade').forEach(item => {
            gsap.from(item, {
                scrollTrigger: {
                    trigger: item,
                    start: "top 85%",
                },
                y: 50,
                opacity: 0,
                duration: 1,
                ease: "power3.out"
            });
        });

        // Glass card slight rotation on scroll
        gsap.utils.toArray('.glass-card').forEach(card => {
            gsap.from(card, {
                scrollTrigger: {
                    trigger: card,
                    start: "top 85%",
                    scrub: 1
                },
                rotationY: 15,
                rotationX: 10,
                z: -100,
                transformOrigin: "center center"
            });
        });
    </script>
</body>
</html>
