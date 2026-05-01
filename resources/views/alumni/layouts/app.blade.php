<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Alumni Dashboard - EduShare')</title>
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
            color: #06141B;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 260px;
            background-color: #ffffff;
            border-right: 1px solid rgba(6, 20, 27, 0.08);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 30px 20px;
            border-bottom: 1px solid rgba(6, 20, 27, 0.05);
            text-align: center;
        }

        .profile-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .profile-circle {
            width: 50px;
            height: 50px;
            background-color: #253745;
            color: #CCD0CF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            font-weight: 800;
            flex-shrink: 0;
            border: 1px solid rgba(6, 20, 27, 0.05);
        }

        .user-name {
            font-weight: 800;
            font-size: 1.1rem;
            color: #06141B;
            margin: 0;
            letter-spacing: -0.5px;
        }

        .role-badge {
            font-size: 0.75rem;
            color: #253745;
            background: #F2F4F3;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .nav-links {
            list-style: none;
            padding: 20px 15px;
            flex: 1;
        }

        .nav-item {
            padding: 0;
            margin-bottom: 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            padding: 14px 18px;
            color: #4A5568;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            border-radius: 12px;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background-color: #F8F9F9;
            color: #06141B;
            transform: translateX(5px);
        }
        
        .nav-link.active {
            background-color: #253745;
            color: #CCD0CF;
            box-shadow: 0 4px 12px rgba(37, 55, 69, 0.15);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(6, 20, 27, 0.05);
        }

        /* ── Main Content Area ── */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
            transition: all 0.3s ease;
        }

        /* ── Top Navbar ── */
        .top-navbar {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(6, 20, 27, 0.05);
            padding: 15px 35px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 90;
            height: 80px;
        }

        .hamburger {
            display: none;
            background: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.1);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 8px 12px;
            border-radius: 10px;
            margin-right: 15px;
            color: #06141B;
        }
        
        .hamburger:hover {
            background: #F2F4F3;
        }

        .page-title {
            font-weight: 800;
            font-size: 1.4rem;
            color: #06141B;
            letter-spacing: -1px;
            flex: 1;
        }

        /* ── Content Wrapper ── */
        .content-wrapper {
            padding: 40px 35px;
            flex: 1;
            max-width: 1300px;
            width: 100%;
            margin: 0 auto;
        }

        /* ── Common Components ── */
        .btn {
            padding: 12px 24px;
            border: none;
            background-color: #253745;
            color: #CCD0CF;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
            box-shadow: 0 4px 12px rgba(37, 55, 69, 0.1);
            font-size: 0.9rem;
        }

        .btn:hover {
            background-color: #1a2833;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(37, 55, 69, 0.2);
        }

        .btn-outline {
            background-color: #ffffff;
            color: #253745;
            border: 1px solid rgba(37, 55, 69, 0.1);
            box-shadow: none;
        }

        .btn-outline:hover {
            background-color: #F2F4F3;
        }

        .btn-danger {
            background-color: #FEF2F2;
            color: #991B1B;
            border: 1px solid #FECACA;
            box-shadow: none;
        }

        .btn-danger:hover {
            background-color: #FEE2E2;
            color: #7F1D1D;
        }
        
        .card {
            background-color: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            border-radius: 24px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 4px 20px rgba(6, 20, 27, 0.04);
            transition: all 0.3s ease;
        }

        .card:hover {
            box-shadow: 0 10px 30px rgba(6, 20, 27, 0.08);
        }

        .alert {
            padding: 20px;
            border-radius: 16px;
            margin-bottom: 30px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .alert-success { 
            background-color: #F0FDF4; 
            color: #166534; 
            border: 1px solid #BBF7D0; 
        }
        
        .alert-danger { 
            background-color: #FEF2F2; 
            color: #991B1B; 
            border: 1px solid #FECACA; 
        }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
                box-shadow: 20px 0 50px rgba(6, 20, 27, 0.15);
            }
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            .hamburger {
                display: block;
            }
            .top-navbar {
                padding: 15px 20px;
            }
            .content-wrapper {
                padding: 30px 20px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

    <!-- Overlay for mobile sidebar -->
    <div id="mobileOverlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: none; z-index: 90;" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <div class="profile-container">
                <div class="profile-circle">
                    {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                </div>
                <div class="user-name">{{ Auth::user()->username }}</div>
            </div>
            <div>
                <span class="role-badge">Alumni Dashboard</span>
            </div>
        </div>
        
        <ul class="nav-links">
            <li class="nav-item">
                <a href="{{ route('alumni.dashboard') }}" class="nav-link {{ request()->routeIs('alumni.dashboard') ? 'active' : '' }}">📊 Dashboard</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('alumni.requests') }}" class="nav-link {{ request()->routeIs('alumni.requests') ? 'active' : '' }}">📩 Mentorship Requests</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('alumni.sessions') }}" class="nav-link {{ request()->routeIs('alumni.sessions') ? 'active' : '' }}">📅 My Sessions</a>
            </li>
            <li class="nav-item">
                <a href="{{ route('alumni.profile') }}" class="nav-link {{ request()->routeIs('alumni.profile') ? 'active' : '' }}">👤 My Profile</a>
            </li>
        </ul>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn" style="width: 100%;">🚪 Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-navbar">
            <button class="hamburger" onclick="toggleSidebar()">☰</button>
            <div class="page-title">@yield('header_title', 'Alumni Dashboard')</div>
            <div style="margin-left: auto; display: flex; align-items: center; gap: 20px;">
                @include('partials.nav-notifications')
                <div style="font-weight: 700; border: 1px solid rgba(6, 20, 27, 0.08); padding: 8px 16px; border-radius: 20px; background: #F2F4F3; font-size: 0.9rem; color: #06141B;">
                   👋 Hi, {{ Auth::user()->username }}
                </div>
            </div>
        </header>

        <section class="content-wrapper">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @yield('content')
        </section>
    </main>

    <!-- Layout Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            if(sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.style.display = 'none';
            } else {
                sidebar.classList.add('show');
                overlay.style.display = 'block';
            }
        }
    </script>
    @stack('scripts')
</body>
</html>
