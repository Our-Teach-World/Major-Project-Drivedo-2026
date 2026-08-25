<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - CampusCore</title>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
        window.OneSignalDeferred = window.OneSignalDeferred || [];
        OneSignalDeferred.push(function (OneSignal) {
            OneSignal.init({
                appId: "{{ config('services.onesignal.app_id') }}",
                notifyButton: {
                    enable: true,
                },
            });

            // Set Tags for Targeting (v16 Syntax)
            @if(auth()->check() && auth()->user()->studentProfile)
                OneSignal.User.addTags({
                    role: 'student',
                    branch: '{{ auth()->user()->studentProfile->branch }}',
                    semester: '{{ auth()->user()->studentProfile->semester }}'
                });
            @endif
    });
    </script>

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
            overflow-x: hidden;
            line-height: 1.5;
        }

        /* ── Layout Shell ── */
        .app-container {
            display: flex;
            min-height: 100vh;
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
            left: 0;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease;
        }

        .sidebar-header {
            padding: 24px 20px;
            border-bottom: 1px solid rgba(6, 20, 27, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .sidebar-title {
            font-weight: 800;
            font-size: 1.5rem;
            color: #253745;
            letter-spacing: -1px;
        }

        .close-sidebar-btn {
            display: none;
            background: #F2F4F3;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            font-size: 1rem;
            cursor: pointer;
            color: #06141B;
        }

        .nav-links {
            padding: 20px 15px;
            flex: 1;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .nav-item {
            padding: 12px 15px;
            border-radius: 12px;
            color: #4A5568;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s;
            cursor: pointer;
            font-size: 0.95rem;
        }

        .nav-item:hover {
            background-color: rgba(37, 55, 69, 0.05);
            color: #253745;
        }

        .nav-item.active {
            background-color: #253745;
            color: #CCD0CF;
            box-shadow: 0 8px 16px rgba(37, 55, 69, 0.1);
        }

        .nav-icon {
            font-size: 1.2rem;
        }

        /* ── Main Content Area ── */
        .main-wrapper {
            flex: 1;
            margin-left: 260px;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            transition: margin 0.3s ease;
        }

        /* ── Topbar ── */
        .topbar {
            background-color: #ffffff;
            border-bottom: 1px solid rgba(6, 20, 27, 0.08);
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(6, 20, 27, 0.02);
        }

        .menu-btn {
            display: none;
            background: #F2F4F3;
            border: 1px solid rgba(6, 20, 27, 0.1);
            font-size: 1.2rem;
            cursor: pointer;
            padding: 6px 10px;
            border-radius: 8px;
            color: #06141B;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
            font-weight: 600;
            color: #06141B;
        }

        .logout-btn {
            padding: 10px 20px;
            border: none;
            background-color: #253745;
            color: #CCD0CF;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px rgba(37, 55, 69, 0.1);
            font-size: 0.9rem;
        }

        .logout-btn:hover {
            background-color: #1a2833;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(37, 55, 69, 0.2);
        }

        /* ── Content Container ── */
        .container {
            padding: 40px 30px;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        /* ── Common Styles ── */
        h1, h2 {
            margin-bottom: 25px;
            font-weight: 800;
            color: #06141B;
            letter-spacing: -0.5px;
        }

        input[type="text"] {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid rgba(6, 20, 27, 0.1);
            background-color: #ffffff;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 1rem;
            color: #06141B;
            transition: all 0.2s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #253745;
            box-shadow: 0 0 0 3px rgba(37, 55, 69, 0.1);
        }

        .semester-filter {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            margin-bottom: 30px;
            align-items: center;
        }

        .sem-btn {
            padding: 8px 20px;
            border: 1px solid rgba(37, 55, 69, 0.1);
            border-radius: 25px;
            background: #ffffff;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            color: #4A5568;
        }

        .sem-btn:hover {
            background: #F2F4F3;
            color: #253745;
        }

        .sem-btn.active {
            background: #253745;
            color: #CCD0CF;
            border-color: #253745;
        }

        .teacher-list, .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 25px;
            margin-bottom: 40px;
        }

        .teacher-card, .folder-card {
            background-color: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            padding: 30px 20px;
            border-radius: 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 4px 20px rgba(6, 20, 27, 0.04);
        }

        .teacher-card:hover, .folder-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(6, 20, 27, 0.1);
            border-color: rgba(37, 55, 69, 0.1);
        }

        .teacher-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            border: 1px solid rgba(6, 20, 27, 0.1);
            margin: 0 auto 15px;
            background-color: #F2F4F3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.2rem;
            font-weight: 800;
            color: #253745;
            overflow: hidden;
        }

        .teacher-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .teacher-name {
            font-weight: 800;
            font-size: 1.15rem;
            color: #06141B;
        }

        .file-list {
            background-color: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            border-radius: 20px;
            padding: 10px;
            box-shadow: 0 4px 20px rgba(6, 20, 27, 0.04);
        }

        .file-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid rgba(6, 20, 27, 0.05);
            transition: background 0.2s;
            border-radius: 12px;
        }

        .file-item:last-child {
            border-bottom: none;
        }

        .file-item:hover {
            background-color: rgba(37, 55, 69, 0.02);
        }

        .download-btn {
            padding: 8px 18px;
            background-color: #F2F4F3;
            color: #253745;
            border: 1px solid rgba(37, 55, 69, 0.1);
            border-radius: 8px;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 700;
            transition: all 0.2s;
        }

        .download-btn:hover {
            background-color: #253745;
            color: #CCD0CF;
            border-color: #253745;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 25px;
            padding: 8px 16px;
            background-color: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.1);
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            color: #4A5568;
            font-size: 0.9rem;
        }

        .back-link:hover {
            background-color: #F2F4F3;
            color: #06141B;
            transform: translateX(-4px);
        }

        .section {
            display: none;
            animation: fadeIn 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .section.active {
            display: block;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── AI Chat ── */
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 550px;
            background: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            border-radius: 24px;
            box-shadow: 0 10px 40px rgba(6, 20, 27, 0.06);
            overflow: hidden;
        }

        .chat-header {
            background: #253745;
            color: #CCD0CF;
            padding: 20px 25px;
            font-weight: 800;
            letter-spacing: -0.5px;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 25px;
            background-color: #F8F9F9;
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .message-wrapper {
            display: flex;
            width: 100%;
        }

        .message-wrapper.user {
            justify-content: flex-end;
        }

        .message-wrapper.assistant, .message-wrapper.system {
            justify-content: flex-start;
        }

        .message {
            padding: 12px 18px;
            border-radius: 20px;
            max-width: 85%;
            font-size: 0.95rem;
            line-height: 1.6;
            white-space: pre-wrap;
            position: relative;
            box-shadow: 0 2px 5px rgba(0,0,0,0.03);
        }

        .message.user {
            background-color: #253745;
            color: #ffffff;
            border-bottom-right-radius: 4px;
        }

        .message.assistant {
            background-color: #ffffff;
            color: #06141B;
            border-bottom-left-radius: 4px;
            border: 1px solid rgba(6, 20, 27, 0.08);
        }

        .message.system {
            background-color: rgba(37, 55, 69, 0.05);
            color: #718096;
            font-size: 0.8rem;
            font-style: italic;
            border-radius: 10px;
            max-width: 95%;
        }

        .chat-input-group {
            display: flex;
            padding: 20px;
            background: #ffffff;
            border-top: 1px solid rgba(6, 20, 27, 0.08);
            gap: 15px;
        }

        .chat-input {
            flex: 1;
            margin: 0 !important;
        }

        .chat-send-btn {
            background: #253745;
            color: #CCD0CF;
            border: none;
            padding: 0 25px;
            font-weight: 700;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .chat-send-btn:hover {
            background: #1a2833;
            transform: scale(1.02);
        }

        /* ── Resume Advisor ── */
        .resume-advisor {
            background: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 10px 40px rgba(6, 20, 27, 0.06);
        }

        .drop-zone {
            border: 2px dashed rgba(37, 55, 69, 0.2);
            padding: 50px 30px;
            text-align: center;
            cursor: pointer;
            background: #F8F9F9;
            border-radius: 20px;
            margin-bottom: 25px;
            transition: all 0.2s;
        }

        .drop-zone:hover {
            background: #F2F4F3;
            border-color: #253745;
        }

        /* ── Notice Board ── */
        .notice-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .notice-card {
            background: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            border-radius: 20px;
            padding: 30px;
            position: relative;
            transition: all 0.3s;
            box-shadow: 0 4px 20px rgba(6, 20, 27, 0.04);
        }

        .notice-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(6, 20, 27, 0.08);
        }

        .notice-badge {
            position: absolute;
            top: 30px;
            right: 30px;
            background: #253745;
            color: #CCD0CF;
            padding: 5px 12px;
            border-radius: 25px;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .notice-title {
            font-size: 1.4rem;
            font-weight: 800;
            margin-bottom: 12px;
            color: #06141B;
            letter-spacing: -0.5px;
            padding-right: 100px;
        }

        .notice-content {
            line-height: 1.7;
            color: #4A5568;
            font-size: 1rem;
        }

        .notice-meta {
            margin-top: 25px;
            border-top: 1px solid rgba(6, 20, 27, 0.08);
            padding-top: 20px;
            font-size: 0.85rem;
            color: #718096;
            display: flex;
            justify-content: space-between;
        }

        .notice-attachment {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #253745;
            text-decoration: none;
            font-weight: 700;
            margin-top: 15px;
            font-size: 0.9rem;
        }

        .notice-attachment:hover {
            text-decoration: underline;
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(6, 20, 27, 0.3);
            backdrop-filter: blur(4px);
            z-index: 999;
            display: none;
            opacity: 0;
            transition: opacity 0.3s;
        }

        @media (max-width: 900px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: 20px 0 50px rgba(6, 20, 27, 0.1); }
            .main-wrapper { margin-left: 0; }
            .menu-btn, .close-sidebar-btn { display: block; }
            .sidebar-overlay.open { display: block; opacity: 1; }
            .teacher-list, .folder-grid { grid-template-columns: 1fr 1fr; }
            .container { padding: 30px 20px; }
            .topbar { padding: 15px 20px; }
        }

        @media (max-width: 500px) {
            .teacher-list, .folder-grid { grid-template-columns: 1fr; }
            .notice-title { padding-right: 0; margin-top: 35px; }
            .notice-badge { top: 20px; right: 20px; }
        }
    </style>
</head>

<body>

    <div class="app-container">

        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">🎓 CampusCore</div>
                <button class="close-sidebar-btn" onclick="toggleSidebar()">✕</button>
            </div>

            <nav class="nav-links">
                <a href="{{ route('student.dashboard') }}?section=study"
                    class="nav-item {{ (Route::is('student.dashboard') && (request('section') == 'study' || !request('section'))) ? 'active' : '' }}"
                    @if(Route::is('student.dashboard'))
                    onclick="event.preventDefault(); navToSection('teacherSection', this);" @endif>
                    <span class="nav-icon">📚</span> Study Materials
                </a>
                <a href="{{ route('student.dashboard') }}?section=chat"
                    class="nav-item {{ (Route::is('student.dashboard') && request('section') == 'chat') ? 'active' : '' }}"
                    @if(Route::is('student.dashboard'))
                    onclick="event.preventDefault(); navToSection('chatSection', this);" @endif>
                    <span class="nav-icon">💬</span> AI Chat
                </a>
                <a href="{{ route('student.dashboard') }}?section=resume"
                    class="nav-item {{ (Route::is('student.dashboard') && request('section') == 'resume') ? 'active' : '' }}"
                    @if(Route::is('student.dashboard'))
                    onclick="event.preventDefault(); navToSection('resumeSection', this);" @endif>
                    <span class="nav-icon">📄</span> Resume Advisor
                </a>

                <hr style="border: 1px dashed #ccc; margin: 10px 0;">

                <a href="{{ route('student.attendance') }}"
                    class="nav-item {{ Route::is('student.attendance') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span> My Attendance
                </a>
                <a href="{{ route('student.timetable') }}"
                    class="nav-item {{ Route::is('student.timetable') ? 'active' : '' }}">
                    <span class="nav-icon">📅</span> Timetable
                </a>
                <a href="{{ route('student.dashboard') }}?section=notices"
                    class="nav-item {{ (Route::is('student.dashboard') && request('section') == 'notices') ? 'active' : '' }}"
                    @if(Route::is('student.dashboard'))
                    onclick="event.preventDefault(); navToSection('noticeSection', this);" @endif>
                    <span class="nav-icon">📢</span> Notice Board
                </a>

                <hr style="border: 1px dashed #ccc; margin: 10px 0;">

                <a href="{{ route('mentorship.browse') }}" class="nav-item {{ Route::is('mentorship.*') ? 'active' : '' }}">
                    <span class="nav-icon">🤝</span> Alumni Mentorship
                </a>

                {{-- Smart Quiz System --}}
                <a href="{{ route('student.quizzes.index') }}" class="nav-item {{ Route::is('student.quizzes.*') ? 'active' : '' }}">
                    <span class="nav-icon">📝</span>Quiz
                </a>

                {{-- Book Exchange Feature --}}
                <a href="{{ route('books.index') }}" class="nav-item {{ Route::is('books.*') ? 'active' : '' }}">
                    <span class="nav-icon">📖</span> Campus Book Exchange
                </a>

                {{-- Project Marketplace: CS & Electronics students only --}}
                @php
                    $studentBranchForNav = optional(auth()->user()->studentProfile)->branch ?? '';
                    $isMarketplaceBranch = stripos($studentBranchForNav, 'CS') !== false
                        || stripos($studentBranchForNav, 'Electronics') !== false
                        || stripos($studentBranchForNav, 'Computer') !== false;
                @endphp
                @if($isMarketplaceBranch)
                    <hr style="border: 1px dashed #ccc; margin: 10px 0;">
                    <a href="{{ route('marketplace') }}" class="nav-item {{ Route::is('marketplace*') ? 'active' : '' }}">
                        <span class="nav-icon">🚀</span> Project Marketplace
                        <span
                            style="margin-left:auto;font-size:10px;background:#253745;color:#CCD0CF;padding:2px 7px;border-radius:999px;font-weight:700;">CS/IT</span>
                    </a>
                @endif
            </nav>
        </aside>

        <main class="main-wrapper">

            <header class="topbar">
                <div style="display: flex; align-items: center; gap: 15px;">
                    <button class="menu-btn" onclick="toggleSidebar()">☰</button>
                    <h2 style="margin: 0; font-size: 1.3rem;">Dashboard</h2>
                </div>

                <div class="user-info" style="display: flex; align-items: center; gap: 20px;">
                    @include('partials.nav-notifications')
                    <span>Hi, {{ Auth::user()->username }}!</span>
                    <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                        @csrf
                        <button type="submit" class="logout-btn">Logout</button>
                    </form>
                </div>
            </header>

            <div class="container">
                @hasSection('content')
                    @yield('content')
                @else
                    <div id="teacherSection" class="section active">
                        <h1>📚 Select a Teacher</h1>
                        <div class="semester-filter">
                            <strong>Filter:</strong>
                            <button class="sem-btn active" onclick="setSemester(null, this)">All</button>
                            @for ($i = 1; $i <= 6; $i++)
                                <button class="sem-btn" onclick="setSemester({{ $i }}, this)">Sem {{ $i }}</button>
                            @endfor
                        </div>
                        <input type="text" id="searchTeacher" placeholder="Search Teacher by Name..." />
                        <div class="teacher-list" id="teacherList">
                            <p style="text-align: center; grid-column: 1/-1;">Loading teachers...</p>
                        </div>
                    </div>

                    <div id="folderSection" class="section">
                        <button onclick="navToSection('teacherSection')" class="back-link">⬅ Back to Teachers</button>
                        <h2 id="folderTitle">Folders</h2>
                        <div class="folder-grid" id="folderGrid"></div>
                    </div>

                    <div id="fileSection" class="section">
                        <button onclick="showSection('folderSection')" class="back-link">⬅ Back to Folders</button>
                        <h2 id="fileTitle">Files</h2>
                        <input type="text" id="searchFiles" placeholder="Search Files..." />
                        <button onclick="chatWithSelected()" class="logout-btn"
                            style="margin-bottom: 15px;">💬 Chat with Selected Files</button>
                        <div class="file-list" id="fileList"></div>
                    </div>

                    <div id="chatSection" class="section">
                        <h2>💬 AI Chat Assistant</h2>
                        <p style="margin-bottom: 15px; color: #666;">Ask questions about your files. The AI will search
                            through available documents.</p>
                        <div class="chat-container">
                            <div class="chat-header">Chat with AI</div>
                            <div class="chat-messages" id="chatMessages"></div>
                            <div class="chat-input-group">
                                <input type="text" id="chatInput" class="chat-input" placeholder="Type your question..." />
                                <button onclick="sendChat()" class="chat-send-btn" id="chatSendBtn">Send</button>
                            </div>
                        </div>
                    </div>

                    <div id="noticeSection" class="section">
                        <h1>📢 Notice Board</h1>
                        <p style="margin-bottom: 20px; color: #666;">Latest announcements and updates from the
                            administration.</p>
                        <div id="noticeList" class="notice-grid">
                            <p style="text-align: center; color: #999;">Loading notices...</p>
                        </div>
                    </div>

                    <div id="resumeSection" class="section">
                        <h2>📄 Resume Advisor</h2>
                        <p style="margin-bottom: 20px; color: #666;">Upload your resume for AI analysis and improvements.</p>
                        
                        <div class="resume-advisor">
                            <!-- Drop Zone -->
                            <div class="drop-zone" id="resumeDropZone" onclick="document.getElementById('resumeFileInput').click()">
                                <div style="font-size: 3rem; margin-bottom: 10px;">📁</div>
                                <div style="font-weight: 700;">Click to upload your resume</div>
                                <div style="font-size: 0.85rem; color: #666; margin-top: 5px;">PDF Only · Max 5 MB</div>
                            </div>
                            <input type="file" id="resumeFileInput" accept=".pdf" style="display:none;" onchange="handleResumeFile(this.files[0])">

                            <!-- File Loaded State -->
                            <div id="resumeLoaded" style="display: none; align-items: center; gap: 15px; background: #f0f4f8; padding: 15px; border-radius: 12px; margin-bottom: 20px; border: 1px solid #d1d9e0;">
                                <div id="resumeLoadedName" style="font-weight: 600; flex: 1; color: #253745;"></div>
                                <button onclick="clearResume()" style="background: none; border: none; color: #e53e3e; font-weight: 700; cursor: pointer; font-size: 0.85rem;">✕ REMOVE</button>
                            </div>

                            <!-- Optional Message -->
                            <div id="resumeOptions" style="margin-bottom: 20px;">
                                <label style="display: block; font-size: 0.75rem; font-weight: 800; margin-bottom: 8px; color: #253745; text-transform: uppercase; letter-spacing: 0.5px;">Additional Notes (Optional)</label>
                                <textarea id="resumeMessage" placeholder="e.g. Focus on my technical skills for a Web Developer role..." 
                                    style="width: 100%; padding: 15px; border: 1px solid rgba(6, 20, 27, 0.1); border-radius: 12px; font-size: 0.9rem; min-height: 100px; resize: vertical;"></textarea>
                            </div>

                            <!-- Action Button -->
                            <button id="analyseBtn" onclick="analyseResume()" disabled 
                                style="width: 100%; padding: 18px; background: #253745; color: #CCD0CF; border: none; border-radius: 12px; font-weight: 800; cursor: pointer; transition: all 0.2s; text-transform: uppercase; letter-spacing: 1px;">
                                ⚡ ANALYSE RESUME
                            </button>

                            <!-- Result Box -->
                            <div id="resumeResponse" style="display: none; margin-top: 35px; border-top: 2px dashed #eee; padding-top: 30px;">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 800; color: #253745;">📋 AI FEEDBACK & SUGGESTIONS</h3>
                                    <button onclick="copyResumeResponse()" style="padding: 8px 16px; background: #ffffff; border: 1px solid #d1d9e0; border-radius: 8px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s;">
                                        COPY TEXT
                                    </button>
                                </div>
                                <div id="resumeResponseBody" style="background: #ffffff; border: 1px solid rgba(6,20,27,0.05); padding: 25px; border-radius: 20px; line-height: 1.8; font-size: 0.95rem; white-space: pre-wrap; color: #2d3748; box-shadow: 0 4px 15px rgba(0,0,0,0.02);"></div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>
    </div>

    <script>
        // --- Sidebar Logic ---
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('open');
        }

        function navToSection(sectionId, navElement = null) {
            const section = document.getElementById(sectionId);
            if (!section) return;

            // Hide all sections
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            // Show target
            section.classList.add('active');

            // Update Active class in sidebar if clicked from sidebar
            if (navElement) {
                document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
                navElement.classList.add('active');

                // Close sidebar on mobile after clicking
                if (window.innerWidth <= 900) {
                    toggleSidebar();
                }

                // Load content if needed
                if (sectionId === 'noticeSection') loadNotices();

                // Update URL without reload to reflect section
                const sectionMap = { 'teacherSection': 'study', 'chatSection': 'chat', 'resumeSection': 'resume', 'noticeSection': 'notices' };
                const secParam = sectionMap[sectionId] || '';
                if (secParam) {
                    const newUrl = window.location.pathname + '?section=' + secParam;
                    window.history.pushState({ path: newUrl }, '', newUrl);
                }
            }
        }

        // Keep original showSection for backward compatibility with your inner buttons
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');
        }

        let selectedTeacher = null;
        let selectedFolder = null;
        let allFiles = [];
        let activeSemester = null;  // null = show all

        function setSemester(sem, btn) {
            activeSemester = sem;
            document.querySelectorAll('.sem-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            loadTeachers();
        }

        function showSection(sectionId) {

            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));

            document.getElementById(sectionId).classList.add('active');

        }



        function showTeachers() {

            showSection('teacherSection');

            loadTeachers();

        }



        function showFolders() {

            showSection('folderSection');

        }



        function loadTeachers() {

            const url = '{{ route("student.teachers") }}' + (activeSemester ? `?semester=${activeSemester}` : '');

            fetch(url)

                .then(r => r.json())

                .then(teachers => {

                    const list = document.getElementById('teacherList');

                    if (!teachers.length) {

                        list.innerHTML = '<p style="text-align:center;grid-column:1/-1;color:#999;">No teachers available' + (activeSemester ? ` for Semester ${activeSemester}` : '') + '.</p>';

                        return;

                    }

                    list.innerHTML = teachers.map(t => {

                        const name = t.display_name || t.username;

                        const initial = name.charAt(0).toUpperCase();

                        const avatarHtml = t.profile_image

                            ? `<img src="/${t.profile_image}" alt="${name}" onerror="this.style.display='none';this.parentNode.textContent='${initial}'">`

                            : initial;

                        return `

                        <div class="teacher-card" onclick="selectTeacher('${t.username}', '${name}')">

                            <div class="teacher-avatar">${avatarHtml}</div>

                            <div class="teacher-name">${name}</div>

                            ${t.display_name ? `<div class="teacher-username">@${t.username}</div>` : ''}

                            ${t.branch ? `<div style="font-size:0.78rem;color:#666;margin-top:4px;">${t.branch}</div>` : ''}

                        </div>`;

                    }).join('');

                })

                .catch(() => {

                    document.getElementById('teacherList').innerHTML =

                        '<p style="text-align:center;grid-column:1/-1;color:#c62828;">Failed to load teachers.</p>';

                });

        }



        function selectTeacher(teacherUsername, teacherDisplayName) {

            selectedTeacher = teacherUsername;

            const label = teacherDisplayName || teacherUsername;

            document.getElementById('folderTitle').textContent = `${label}'s Folders`;

            loadFolders();

            showSection('folderSection');

        }



        function loadFolders() {

            const semParam = activeSemester ? `&semester=${activeSemester}` : '';

            fetch(`{{ route("student.files") }}?action=folders&teacher=${selectedTeacher}${semParam}`)

                .then(r => r.json())

                .then(folders => {

                    const grid = document.getElementById('folderGrid');

                    grid.innerHTML = folders.map(f => `

                        <div class="folder-card" onclick="selectFolder('${f.name}')">

                            <div class="folder-icon">${f.icon}</div>

                            <div class="folder-name">${f.name}</div>

                            <div class="folder-count">${f.count} file(s)</div>

                        </div>

                    `).join('');

                });

        }



        function selectFolder(folderName) {

            selectedFolder = folderName;

            document.getElementById('fileTitle').textContent = `${selectedTeacher} - ${folderName}`;

            loadFiles();

            showSection('fileSection');

        }



        function loadFiles() {

            const semParam = activeSemester ? `&semester=${activeSemester}` : '';

            fetch(`{{ route("student.files") }}?action=files&teacher=${selectedTeacher}&folder=${selectedFolder}${semParam}`)

                .then(r => r.json())

                .then(files => {

                    allFiles = files;

                    renderFiles(files);

                });

        }



        function renderFiles(files) {

            const list = document.getElementById('fileList');

            if (files.length === 0) {

                list.innerHTML = '<p class="empty-msg">No files in this folder' + (activeSemester ? ` for Semester ${activeSemester}` : '') + '.</p>';

                return;

            }

            list.innerHTML = files.map(f => {

                const semBadge = f.semester ? `<span class="sem-badge">Sem ${f.semester}</span>` : '';

                return `

                <div class="file-item">

                    <input type="checkbox" class="file-checkbox" value="${f.filename}" style="margin-right: 10px;">

                    <div class="file-name">📄 ${f.filename}${semBadge}</div>

                    <a href="/uploads/${selectedTeacher}/${f.filepath}" target="_blank" class="download-btn">Download</a>

                </div>`;

            }).join('');

        }



        function chatWithSelected() {

            selectedFiles = Array.from(document.querySelectorAll('.file-checkbox:checked')).map(cb => cb.value);

            if (selectedFiles.length === 0) {

                alert('Please select at least one file to chat with.');

                return;

            }

            showSection('chatSection');

        }



        // --- Notice Logic ---
        function loadNotices() {
            const list = document.getElementById('noticeList');
            if (!list) return;

            fetch('{{ route("student.notices") }}')
                .then(r => r.json())
                .then(notices => {
                    if (notices.length === 0) {
                        list.innerHTML = '<p style="text-align: center; color: #999; padding: 40px;">No notices available for you at the moment.</p>';
                        return;
                    }
                    list.innerHTML = notices.map(n => `
                        <div class="notice-card">
                            <div class="notice-badge">NEW</div>
                            <div class="notice-title">${n.title}</div>
                            <div class="notice-content">${n.content}</div>
                            ${n.attachment_path ? `<a href="/${n.attachment_path}" target="_blank" class="notice-attachment">📎 View Attachment</a>` : ''}
                            <div class="notice-meta">
                                <span>
                                    By: ${(function () {
                            const creator = n.creator;
                            if (!creator) return 'Administration';

                            const role = (creator.role || '').toLowerCase().trim();
                            if (role === 'principal') return 'Principal';
                            if (role === 'hod') return `HOD of ${creator.branch || 'Department'}`;
                            if (role === 'teacher') {
                                const name = (creator.teacher_profile && creator.teacher_profile.display_name) ? creator.teacher_profile.display_name : creator.username;
                                return `Prof. ${name}`;
                            }
                            return creator.username;
                        })()}
                                </span>
                                <span>${new Date(n.created_at).toLocaleDateString(undefined, {
                            day: 'numeric', month: 'short', year: 'numeric'
                        })}</span>
                            </div>
                        </div>
                    `).join('');
                })
                .catch(err => {
                    list.innerHTML = '<p style="text-align: center; color: #c62828; padding: 40px;">Failed to load notices.</p>';
                });
        }

        // Initialize
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Handle URL Section Parameter
            const urlParams = new URLSearchParams(window.location.search);
            const sectionParam = urlParams.get('section');
            if (sectionParam) {
                const sectionMap = { 'study': 'teacherSection', 'chat': 'chatSection', 'resume': 'resumeSection', 'notices': 'noticeSection' };
                const targetId = sectionMap[sectionParam];
                if (targetId) {
                    navToSection(targetId);
                    if (targetId === 'noticeSection') loadNotices();
                    // Also update sidebar active class
                    document.querySelectorAll('.nav-links .nav-item').forEach(item => {
                        if (item.getAttribute('href') && item.getAttribute('href').includes('section=' + sectionParam)) {
                            document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
                            item.classList.add('active');
                        }
                    });
                }
            }

            // 2. Load Teachers and Handle Deep Linking
            if (document.getElementById('teacherList')) {
                loadTeachers();

                // If a teacher parameter is present, automatically select them
                const teacherParam = urlParams.get('teacher');
                if (teacherParam && sectionParam === 'study') {
                    // We call selectTeacher directly with the username. 
                    // The teacherDisplayName will fallback to username until the list loads correctly.
                    selectTeacher(teacherParam, teacherParam);
                }
            }
        });



        let chatHistory = [];

        let selectedFiles = [];



        function sendChat() {

            const input = document.getElementById('chatInput');

            const btn = document.getElementById('chatSendBtn');

            const message = input.value.trim();



            if (!message) return;



            // Disable button

            btn.disabled = true;

            btn.textContent = 'Sending...';



            // Add user message to chat

            addMessage('user', message);



            // Send to API via POST with JSON body

            fetch('{{ route("chat.api") }}', {

                method: 'POST',

                headers: {

                    'Content-Type': 'application/json',

                    'Accept': 'application/json',

                    'X-CSRF-TOKEN': '{{ csrf_token() }}'

                },

                body: JSON.stringify({

                    message: message,

                    history: chatHistory,

                    contextFiles: selectedFiles

                })

            })

                .then(r => r.json())

                .then(data => {

                    if (data.error) {

                        addMessage('system', 'Error: ' + data.error);

                    } else {

                        if (data.reasoning) {

                            addMessage('system', 'Reasoning: ' + data.reasoning);

                        }

                        addMessage('assistant', data.response);

                        if (data.foundFiles && data.foundFiles.length > 0) {

                            addMessage('system', 'Sources: ' + data.foundFiles.join(', '));

                        }

                    }

                })

                .catch(e => {

                    addMessage('system', 'Network error: ' + e.message);

                })

                .finally(() => {

                    // Re-enable button

                    btn.disabled = false;

                    btn.textContent = 'Send';

                    input.value = '';

                });

        }



        function addMessage(type, content) {
            const messages = document.getElementById('chatMessages');
            
            const wrapper = document.createElement('div');
            wrapper.className = 'message-wrapper ' + type;

            const msgDiv = document.createElement('div');
            msgDiv.className = 'message ' + type;
            msgDiv.textContent = content;

            wrapper.appendChild(msgDiv);
            messages.appendChild(wrapper);
            messages.scrollTop = messages.scrollHeight;

            // Add to history
            chatHistory.push({ role: type === 'user' ? 'user' : 'assistant', content: content });
        }



        // Allow Enter key to send

        document.getElementById('chatInput').addEventListener('keypress', function (e) {

            if (e.key === 'Enter') sendChat();

        });



        // ── Resume Advisor Logic ─────────────────────────────────────────────

        let resumeFile = null;



        const dropZone = document.getElementById('resumeDropZone');

        dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('dragover'); });

        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));

        dropZone.addEventListener('drop', e => {

            e.preventDefault();

            dropZone.classList.remove('dragover');

            const f = e.dataTransfer.files[0];

            if (f) handleResumeFile(f);

        });



        function handleResumeFile(file) {

            const allowed = ['application/pdf'];

            const allowedExt = ['.pdf'];

            const ext = '.' + file.name.split('.').pop().toLowerCase();



            if (!allowedExt.includes(ext)) {

                alert('Unsupported file type. Please upload a PDF file.');

                return;

            }

            if (file.size > 5 * 1024 * 1024) {

                alert('File is too large. Maximum size is 5 MB.');

                return;

            }



            resumeFile = file;

            document.getElementById('resumeLoaded').style.display = 'flex';

            document.getElementById('resumeLoadedName').textContent = '📄 ' + file.name;

            document.getElementById('analyseBtn').disabled = false;

            document.getElementById('resumeResponse').style.display = 'none';

        }



        function clearResume() {

            resumeFile = null;

            document.getElementById('resumeFileInput').value = '';

            document.getElementById('resumeLoaded').style.display = 'none';

            document.getElementById('analyseBtn').disabled = true;

            document.getElementById('resumeResponse').style.display = 'none';

        }



        function analyseResume() {

            if (!resumeFile) { alert('Please upload a resume first.'); return; }



            const btn = document.getElementById('analyseBtn');

            const body = document.getElementById('resumeResponseBody');

            const msg = document.getElementById('resumeMessage').value.trim();



            btn.disabled = true;

            btn.textContent = '⏳ Analysing… please wait (this may take 30–60 seconds)';

            document.getElementById('resumeResponse').style.display = 'none';



            const form = new FormData();

            form.append('resume', resumeFile);

            form.append('message', msg || 'Please review and improve this resume.');

            form.append('_token', '{{ csrf_token() }}');



            fetch('{{ route("student.resume.chat") }}', {

                method: 'POST',

                headers: { 'Accept': 'application/json' },

                body: form

            })

                .then(r => r.json())

                .then(data => {

                    if (data.error) {

                        body.textContent = '❌ Error: ' + data.error + (data.details ? '\n\n' + data.details : '');

                    } else {

                        body.textContent = data.response;

                    }

                    document.getElementById('resumeResponse').style.display = 'block';

                })

                .catch(e => {

                    body.textContent = '❌ Network error: ' + e.message;

                    document.getElementById('resumeResponse').style.display = 'block';

                })

                .finally(() => {

                    btn.disabled = false;

                    btn.textContent = '⚡ Analyse Resume';

                });

        }



        function copyResumeResponse() {

            const text = document.getElementById('resumeResponseBody').textContent;

            navigator.clipboard.writeText(text).then(() => alert('Copied to clipboard!'));

        }

        // (Paste ALL your previous loadTeachers(), loadFolders(), loadFiles(), sendChat(), and Resume JS right here)
        // I have preserved the HTML IDs so your existing JS will map perfectly to this new UI.
    </script>
</body>

</html>