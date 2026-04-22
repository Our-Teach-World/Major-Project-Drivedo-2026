<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard - EduShare')</title>
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
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #f5f5f5;
            color: #000000;
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ── Sidebar ── */
        .sidebar {
            width: 260px;
            background-color: #ffffff;
            border-right: 2px solid #000000;
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
            border-bottom: 2px solid #000000;
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
            background-color: #000000;
            color: #ffffff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            font-weight: bold;
            border: 2px solid #000000;
            flex-shrink: 0;
        }

        .admin-name {
            font-weight: 700;
            font-size: 1.1rem;
            margin: 0;
        }

        .admin-branch {
            font-size: 0.8rem;
            color: #333333;
            background: #e0e0e0;
            padding: 4px 10px;
            border-radius: 15px;
            display: inline-block;
            border: 1px solid #000;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .nav-links {
            list-style: none;
            padding: 15px;
            flex: 1;
        }

        .nav-item {
            padding: 0;
            margin-bottom: 12px;
        }

        .nav-link {
            display: block;
            padding: 12px 15px;
            color: #000000;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            border-radius: 8px;
            border: 2px solid transparent;
        }

        .nav-link:hover {
            border: 2px solid #000000;
            box-shadow: 3px 3px 0px #000;
            background-color: #ffffff;
            transform: translateY(-2px);
        }
        
        .nav-link.active {
            background-color: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            box-shadow: 4px 4px 0px rgba(0,0,0,0.2);
        }

        .sidebar-footer {
            padding: 15px 20px;
            border-top: 2px solid #000000;
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
            border-bottom: 2px solid #000000;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .hamburger {
            display: none;
            background: none;
            border: 2px solid #000;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 2px 8px;
            border-radius: 5px;
            margin-right: 15px;
        }
        
        .hamburger:hover {
            background: #000;
            color: #fff;
        }

        .page-title {
            font-weight: 700;
            font-size: 1.3rem;
            flex: 1;
        }

        /* ── Content Wrapper ── */
        .content-wrapper {
            padding: 30px 20px;
            flex: 1;
            max-width: 1400px;
            width: 100%;
            margin: 0 auto;
        }

        /* ── Common Components ── */
        .btn {
            padding: 8px 16px;
            border: 2px solid #000000;
            background-color: #ffffff;
            color: #000000;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            transition: 0.3s;
            display: inline-block;
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
            background-color: #333333; 
        }
        
        .btn-success { 
            background-color: #2e7d32; 
            color: #ffffff; 
            border-color: #2e7d32; 
        }
        .btn-success:hover { 
            background-color: #1b5e20; 
        }
        
        .btn-danger { 
            background-color: #c62828; 
            color: #ffffff; 
            border-color: #c62828; 
        }
        .btn-danger:hover { 
            background-color: #ad1457; 
        }

        .card {
            background-color: #ffffff;
            border: 2px solid #000000;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 30px;
            box-shadow: 4px 4px 0px #000;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 2px solid #000;
            font-weight: bold;
        }

        .alert-success { background-color: #d1fae5; color: #065f46; }
        .alert-danger { background-color: #fee2e2; color: #991b1b; }

        /* ── Custom Modal ── */
        .modal-overlay {
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.6);
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
            border: 2px solid #000;
            box-shadow: 8px 8px 0px #000;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        }

        .modal-overlay.active .modal-box {
            transform: translateY(0);
        }

        .modal-title { font-size: 1.5rem; font-weight: bold; margin-bottom: 15px; color: #dc2626; }
        .modal-body { margin-bottom: 25px; color: #333; font-size: 1.05rem; }
        
        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 15px;
        }

        /* ── Responsive ── */
        @media (max-width: 992px) {
            .sidebar {
                transform: translateX(-100%);
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
                    <span style="font-weight: 600; border: 2px solid #000; padding: 5px 10px; border-radius: 20px; background: #f0f0f0;">
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
