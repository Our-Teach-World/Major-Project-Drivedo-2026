<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - EduShare')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(function(OneSignal) {
        OneSignal.init({
            appId: "{{ config('services.onesignal.app_id') }}",
        });

        // Set Tags for Targeting (v16 Syntax)
        @php
            $adminUser = \App\Models\Admin::find(session('admin_id'));
        @endphp
        @if($adminUser)
            OneSignal.User.addTags({
                role: '{{ $adminUser->role }}',
                branch: '{{ $adminUser->branch ?? "All" }}'
            });
        @endif
    });
    </script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            padding: 20px;
            border-bottom: 1px solid rgba(6, 20, 27, 0.08);
            text-align: center;
        }

        .profile-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 10px;
        }

        .profile-circle {
            width: 45px;
            height: 45px;
            background-color: #253745;
            color: #CCD0CF;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: bold;
            flex-shrink: 0;
        }

        .admin-name {
            font-weight: 800;
            font-size: 1.1rem;
            color: #06141B;
            margin: 0;
        }

        .admin-branch {
            font-size: 0.75rem;
            color: #253745;
            background: #F2F4F3;
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            font-weight: 700;
            letter-spacing: 0.5px;
            text-transform: uppercase;
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
            display: block;
            padding: 12px 15px;
            color: #4A5568;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            border-radius: 12px;
        }

        .nav-link:hover {
            background-color: rgba(37, 55, 69, 0.05);
            color: #253745;
        }
        
        .nav-link.active {
            background-color: #253745;
            color: #CCD0CF;
            box-shadow: 0px 8px 16px rgba(37, 55, 69, 0.15);
        }

        .sidebar-footer {
            padding: 20px;
            border-top: 1px solid rgba(6, 20, 27, 0.08);
        }

        /* ── Main Content Area ── */
        .main-content {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: calc(100% - 260px);
            transition: margin-left 0.3s ease, width 0.3s ease;
        }

        /* ── Top Navbar ── */
        .top-navbar {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(6, 20, 27, 0.08);
            padding: 15px 30px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 90;
            box-shadow: 0 2px 10px rgba(6, 20, 27, 0.02);
        }

        .hamburger {
            display: none;
            background: #F2F4F3;
            border: 1px solid rgba(6, 20, 27, 0.1);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 8px;
            margin-right: 15px;
            color: #06141B;
        }
        
        .hamburger:hover {
            background: #E2E8F0;
        }

        .page-title {
            font-weight: 800;
            font-size: 1.4rem;
            color: #06141B;
            letter-spacing: -0.5px;
            flex: 1;
        }

        /* ── Content Wrapper ── */
        .content-wrapper {
            padding: 40px 30px;
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        /* ── Common Components ── */
        .btn {
            padding: 10px 20px;
            border: 1px solid rgba(37, 55, 69, 0.1);
            background-color: #F8F9F9;
            color: #253745;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            display: inline-block;
            font-size: 0.9rem;
        }

        .btn:hover {
            background-color: #E2E8F0;
            transform: translateY(-1px);
        }

        .btn-primary { 
            background-color: #253745; 
            color: #CCD0CF; 
            border: none;
            box-shadow: 0 4px 12px rgba(37, 55, 69, 0.15);
        }

        .btn-primary:hover { 
            background-color: #1a2833; 
            box-shadow: 0 6px 15px rgba(37, 55, 69, 0.25);
        }
        
        .btn-success { 
            background-color: #059669; 
            color: #ffffff; 
            border: none;
        }
        .btn-success:hover { 
            background-color: #047857; 
        }
        
        .btn-danger { 
            background-color: #DC2626; 
            color: #ffffff; 
            border: none;
        }
        .btn-danger:hover { 
            background-color: #B91C1C; 
        }

        .card {
            background-color: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0px 4px 25px rgba(6, 20, 27, 0.04);
        }

        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .alert-success { background-color: #ECFDF5; color: #065F46; border-color: #A7F3D0; }
        .alert-danger { background-color: #FEF2F2; color: #991B1B; border-color: #FECACA; }

        /* ── Custom Modal ── */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(6, 20, 27, 0.4);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .modal-overlay.active {
            display: flex;
            opacity: 1;
        }

        .modal-box {
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 20px 50px rgba(6, 20, 27, 0.15);
            padding: 40px;
            width: 90%;
            max-width: 450px;
            text-align: center;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-box {
            transform: translateY(0);
        }

        .modal-title { font-size: 1.6rem; font-weight: 800; margin-bottom: 15px; color: #06141B; letter-spacing: -0.5px; }
        .modal-body { margin-bottom: 30px; color: #4A5568; font-size: 1.1rem; line-height: 1.6; }
        
        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 20px 0 50px rgba(6, 20, 27, 0.1);
            }
            .sidebar.show {
                transform: translateX(0);
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
        }
    </style>
    @stack('styles')
</head>
<body>

    @php
        $adminUser = \App\Models\Admin::find(session('admin_id'));
    @endphp

    <!-- Overlay for mobile sidebar -->
    <div id="mobileOverlay" class="modal-overlay" style="z-index: 90;" onclick="toggleSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            @if($adminUser)
                <div class="profile-container">
                    <div class="profile-circle">
                        {{ strtoupper(substr($adminUser->username, 0, 1)) }}
                    </div>
                    <div class="admin-name">{{ $adminUser->username }}</div>
                </div>
                @if(session('admin_role') === 'principal')
                    <div>
                        <span class="admin-branch">Principal Panel</span>
                    </div>
                @elseif($adminUser->branch)
                    <div>
                        <span class="admin-branch">{{ strtoupper($adminUser->branch) }} Dashboard</span>
                    </div>
                @endif
            @else
                <div class="admin-name">Admin</div>
            @endif
        </div>
        
        <ul class="nav-links">
            @if(session('admin_role') === 'principal')
                <!-- Principal Links -->
                <li class="nav-item">
                    <a href="{{ route('principal.dashboard') }}" class="nav-link {{ request()->routeIs('principal.dashboard') ? 'active' : '' }}">📊 Principal Home</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('principal.hods') }}" class="nav-link {{ request()->routeIs('principal.hods') ? 'active' : '' }}">👥 Manage HODs</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('principal.notices.create') }}" class="nav-link {{ request()->routeIs('principal.notices.create') ? 'active' : '' }}">📢 Publish Notice</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">📜 College Records</a>
                </li>
            @else
                <!-- HOD Links -->
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Dashboard Overview</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active' : '' }}">👥 Manage Users</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.notices.board') }}" class="nav-link {{ request()->routeIs('admin.notices.board') ? 'active' : '' }}">📰 Board Notices feed</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.notices.create') }}" class="nav-link {{ request()->routeIs('admin.notices.create') ? 'active' : '' }}">📢 Publish Notice</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.add-user') }}" class="nav-link {{ request()->routeIs('admin.add-user') ? 'active' : '' }}">➕ Add New User</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.subjects') }}" class="nav-link {{ request()->routeIs('admin.subjects') ? 'active' : '' }}">📚 Manage Subjects</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.timetable.setup') }}" class="nav-link {{ request()->routeIs('admin.timetable.setup') ? 'active' : '' }}">🗓️ Schedule Timetable</a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.certchain.templates.index') }}" class="nav-link {{ request()->routeIs('admin.certchain.templates.*') ? 'active' : '' }}">📜 CertChain Templates</a>
                </li>
            @endif
        </ul>

        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn" style="width: 100%;">🚪 Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="top-navbar">
            <button class="hamburger" onclick="toggleSidebar()">☰</button>
            <div class="page-title">@yield('header_title', 'EduShare Admin')</div>
            <div style="margin-left: auto; display: flex; align-items: center; gap: 20px;">
                @if(session('admin_role') !== 'principal')
                    @include('partials.nav-notifications')
                @endif
                @if($adminUser && request()->routeIs('admin.dashboard'))
                    <span style="font-weight: 800; border: 1px solid rgba(6, 20, 27, 0.1); padding: 6px 15px; border-radius: 20px; background: #F2F4F3; font-size: 0.85rem; color: #253745;">
                       👋 Hi, {{ $adminUser->username }}
                    </span>
                @endif
            </div>
        </header>

        <section class="content-wrapper">
            @yield('content')
        </section>
    </main>

    <!-- Custom Confirmation Modal -->
    <div class="modal-overlay" id="confirmModal">
        <div class="modal-box">
            <div class="modal-title">⚠️ Confirm Action</div>
            <div class="modal-body" id="confirmMessage">Are you sure you want to proceed?</div>
            <div class="modal-actions">
                <button class="btn" onclick="closeConfirmModal()">Cancel</button>
                <button class="btn btn-danger" id="confirmBtn">Yes, Delete</button>
            </div>
        </div>
    </div>

    <!-- Layout Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('mobileOverlay');
            if(sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                overlay.classList.remove('active');
            } else {
                sidebar.classList.add('show');
                overlay.classList.add('active');
            }
        }

        // Custom Confirm Modal Logic
        let confirmActionCallback = null;

        function showConfirmModal(message, onConfirm) {
            document.getElementById('confirmMessage').innerText = message;
            document.getElementById('confirmModal').classList.add('active');
            confirmActionCallback = onConfirm;
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('active');
            confirmActionCallback = null;
        }

        document.getElementById('confirmBtn').addEventListener('click', function() {
            if(confirmActionCallback) confirmActionCallback();
            closeConfirmModal();
        });

        // Intercept all forms with class 'confirm-delete-form'
        document.addEventListener('submit', function(e) {
            if(e.target && e.target.classList.contains('confirm-delete-form')) {
                e.preventDefault();
                showConfirmModal(
                    e.target.dataset.confirmMessage || 'Are you sure you want to delete this item?',
                    function() {
                        e.target.submit();
                    }
                );
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
