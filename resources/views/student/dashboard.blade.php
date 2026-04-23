<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - EduShare</title>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(function(OneSignal) {
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
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
        }

        body { 
            background-color: #f5f5f5; 
            color: #000000; 
            overflow-x: hidden; 
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
            border-right: 2px solid #000000;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0; height: 100vh;
            z-index: 1000;
            transition: transform 0.3s ease;
        }
        .sidebar-header {
            padding: 20px;
            border-bottom: 2px solid #000000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar-title { 
            font-weight: 800; 
            font-size: 1.5rem; 
            letter-spacing: -0.5px; 
        }
        .close-sidebar-btn { 
            display: none; 
            background: none; 
            border: none; 
            font-size: 1.5rem; 
            cursor: pointer; 
        }
        
        .nav-links { 
            padding: 20px 15px; 
            flex: 1; 
            overflow-y: auto; 
            display: flex; 
            flex-direction: column; 
            gap: 10px; 
        }

        .nav-item {
            padding: 12px 15px;
            border-radius: 8px;
            color: #000;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.2s;
            cursor: pointer;
            border: 2px solid transparent;
        }
        .nav-item:hover, .nav-item.active { 
            background-color: #f0f0f0; 
            border-color: #000; 
        }

        .nav-icon { 
            font-size: 1.2rem; }

        /* ── Main Content Area ── */
        .main-wrapper { 
            flex: 1; 
            margin-left: 260px; 
            display: flex; 
            flex-direction: column; 
            min-height: 100vh; 
            transition: margin 0.3s ease; }
        
        /* ── Topbar ── */
        .topbar {
            background-color: #ffffff;
            border-bottom: 2px solid #000000;
            padding: 15px 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky; top: 0; z-index: 100;
        }
        .menu-btn { 
            display: none; 
            background: none; 
            border: none; 
            font-size: 1.8rem; 
            cursor: pointer; 
        }
        .user-info { 
            display: flex; 
            align-items: center; 
            gap: 15px; 
            font-weight: 600; 
        }
        
        .logout-btn {
            padding: 8px 16px; 
            border: 2px solid #000000; 
            background-color: #ffffff; 
            color: #000000;
            border-radius: 5px; 
            font-weight: 600; 
            cursor: pointer; 
            transition: 0.3s;
        }
        .logout-btn:hover { 
            background-color: #000000; 
            color: #ffffff; 
        }

        /* ── Content Container ── */
        .container { 
            padding: 30px; 
            max-width: 1200px; 
            margin: 0 auto; 
            width: 100%; 
        }
        
        /* ── Existing Styles (Inputs, Cards, Chat, Resume) ── */
        h1, h2 { 
            margin-bottom: 20px; 
            font-weight: 800; 
        }

        input[type="text"] { 
            width: 100%; 
            padding: 12px; 
            border: 2px solid #000000; 
            border-radius: 5px; 
            margin-bottom: 20px; 
            font-size: 1rem; 
        }
        
        .semester-filter { 
            display: flex; 
            gap: 10px; 
            flex-wrap: wrap; 
            margin-bottom: 20px; 
            align-items: center; 
        }
        .sem-btn { 
            padding: 7px 18px; 
            border: 2px solid #000; 
            border-radius: 20px; 
            background: #fff; 
            font-weight: 700; 
            font-size: 0.88rem; 
            cursor: pointer; 
            transition: 0.2s; 
        }
        .sem-btn:hover, .sem-btn.active { 
            background: #000; 
            color: #fff; 
        }
        
        .teacher-list, .folder-grid { 
            display: grid; 
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); 
            gap: 20px; 
            margin-bottom: 30px; 
        }
        .teacher-card, .folder-card { 
            background-color: #ffffff; 
            border: 2px solid #000000; 
            padding: 20px; 
            border-radius: 8px; 
            text-align: center; 
            cursor: pointer; 
            transition: 0.3s; 
        }
        .teacher-card:hover, .folder-card:hover { 
            transform: translateY(-5px); 
            box-shadow: 4px 4px 0px rgba(0, 0, 0, 1); 
        }
        .teacher-avatar { 
            width: 80px; 
            height: 80px; 
            border-radius: 50%; 
            border: 3px solid #000000; 
            margin: 0 auto 12px; 
            background-color: #e0e0e0; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            font-size: 2rem; 
            font-weight: 700; 
            overflow: hidden; 
        }

        .teacher-avatar img { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
        }
        .teacher-name { 
            font-weight: 700; 
            font-size: 1.1rem; 
        }
        
        .file-list { 
            background-color: #ffffff; 
            border: 2px solid #000000; 
            border-radius: 8px; 
            padding: 20px; 
        }
        .file-item { 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
            padding: 15px; 
            border-bottom: 2px dashed #e0e0e0; 
        }
        .file-item:last-child { 
            border-bottom: none; 
        }
        .download-btn { 
            padding: 8px 16px; 
            background-color: #000000; 
            color: #ffffff; 
            border: 2px solid #000000; 
            border-radius: 5px; 
            text-decoration: none; 
            font-size: 0.9rem; 
            font-weight: 600; 
            transition: 0.3s; 
        }
        .download-btn:hover { 
            background-color: #ffffff; 
            color: #000000; 
        }
        
        .back-link { 
            display: inline-block; 
            margin-bottom: 20px; 
            padding: 8px 16px; 
            background-color: #fff; 
            border: 2px solid #000000; 
            border-radius: 5px; 
            font-weight: 700; 
            cursor: pointer; 
            transition: 0.3s; 
        }
        .back-link:hover { 
            background-color: #000000; 
            color: #ffffff; 
        }

        .section { 
            display: none; 
            animation: fadeIn 0.3s ease; 
        }
        .section.active { 
            display: block; 
        }
        @keyframes fadeIn { 
            from { 
                opacity: 0; 
                transform: translateY(10px); 
            } 
            to { 
                opacity: 1; 
                transform: translateY(0); 
            } 
        }

        /* Mobile Sidebar Overlay */
        .sidebar-overlay { 
         position: fixed; 
         top: 0; 
         left: 0; 
         width: 100vw; 
         height: 100vh; 
         background: rgba(0,0,0,0.5); 
         z-index: 999; 
         display: none; 
         opacity: 0; 
         transition: opacity 0.3s; }

        /* ── Responsiveness ── */
        @media (max-width: 900px) {
            .sidebar { 
                transform: translateX(-100%); 
            }
            .sidebar.open { 
                transform: translateX(0); 
            }
            .main-wrapper { 
                margin-left: 0; 
            }
            .menu-btn { 
                display: block; 
            }
            .close-sidebar-btn { 
                display: block; 
            }
            .sidebar-overlay.open { 
                display: block; opacity: 1; 
            }
            
            .teacher-list, .folder-grid { 
                grid-template-columns: 1fr 1fr; 
            }
        }
        @media (max-width: 500px) {
            .teacher-list, .folder-grid { grid-template-columns: 1fr; }
            .file-item { flex-direction: column; align-items: flex-start; gap: 10px; }
            .download-btn { width: 100%; text-align: center; }
            .user-info span { display: none; } /* Hide name on small screens */
        }

        /* (Keep your existing Chat and Resume CSS here - truncated for brevity but they work exactly the same) */
        .chat-container { display: flex; flex-direction: column; height: 500px; background: #fff; border: 2px solid #000; border-radius: 8px; }
        .chat-header { background: #000; color: #fff; padding: 15px; font-weight: 700; }
        .chat-messages { flex: 1; overflow-y: auto; padding: 15px; }
        .chat-input-group { display: flex; padding: 15px; border-top: 2px solid #000; gap: 10px; }
        .chat-input { flex: 1; margin: 0; }
        .chat-send-btn { background: #000; color: #fff; border: 2px solid #000; padding: 0 20px; font-weight: 700; border-radius: 5px; cursor: pointer; }
        
        .resume-advisor { background: #fff; border: 2px solid #000; border-radius: 8px; padding: 30px; }
        .drop-zone { border: 3px dashed #000; padding: 40px 20px; text-align: center; cursor: pointer; background: #fafafa; margin-bottom: 20px; }
        .notice-grid { display: grid; grid-template-columns: 1fr; gap: 15px; }
        .notice-card { background: #fff; border: 2px solid #000; border-radius: 8px; padding: 20px; position: relative; transition: 0.3s; }
        .notice-card:hover { transform: translateY(-3px); box-shadow: 4px 4px 0px #000; }
        .notice-badge { position: absolute; top: 20px; right: 20px; background: #000; color: #fff; padding: 4px 10px; border-radius: 20px; font-size: 0.7rem; font-weight: 700; }
        .notice-title { font-size: 1.25rem; font-weight: 800; margin-bottom: 10px; padding-right: 80px; }
        .notice-content { line-height: 1.6; color: #333; }
        .notice-meta { margin-top: 15px; border-top: 1px dashed #ccc; padding-top: 15px; font-size: 0.85rem; color: #666; display: flex; justify-content: space-between; }
        .notice-attachment { display: inline-flex; align-items: center; gap: 5px; color: #000; text-decoration: none; font-weight: 700; margin-top: 10px; }
    </style>
</head>
<body>

    <div class="app-container">
        
        <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div class="sidebar-title">🎓 EduShare</div>
                <button class="close-sidebar-btn" onclick="toggleSidebar()">✕</button>
            </div>
            
            <nav class="nav-links">
                <a href="{{ route('student.dashboard') }}?section=study" 
                   class="nav-item {{ (Route::is('student.dashboard') && (request('section') == 'study' || !request('section'))) ? 'active' : '' }}" 
                   @if(Route::is('student.dashboard')) onclick="event.preventDefault(); navToSection('teacherSection', this);" @endif>
                    <span class="nav-icon">📚</span> Study Materials
                </a>
                <a href="{{ route('student.dashboard') }}?section=chat" 
                   class="nav-item {{ (Route::is('student.dashboard') && request('section') == 'chat') ? 'active' : '' }}" 
                   @if(Route::is('student.dashboard')) onclick="event.preventDefault(); navToSection('chatSection', this);" @endif>
                    <span class="nav-icon">💬</span> AI Chat
                </a>
                <a href="{{ route('student.dashboard') }}?section=resume" 
                   class="nav-item {{ (Route::is('student.dashboard') && request('section') == 'resume') ? 'active' : '' }}" 
                   @if(Route::is('student.dashboard')) onclick="event.preventDefault(); navToSection('resumeSection', this);" @endif>
                    <span class="nav-icon">📄</span> Resume Advisor
                </a>
                
                <hr style="border: 1px dashed #ccc; margin: 10px 0;">
                
                <a href="{{ route('student.attendance') }}" class="nav-item {{ Route::is('student.attendance') ? 'active' : '' }}">
                    <span class="nav-icon">📊</span> My Attendance
                </a>
                <a href="{{ route('student.timetable') }}" class="nav-item {{ Route::is('student.timetable') ? 'active' : '' }}">
                    <span class="nav-icon">📅</span> Timetable
                </a>
                <a href="{{ route('student.dashboard') }}?section=notices" 
                   class="nav-item {{ (Route::is('student.dashboard') && request('section') == 'notices') ? 'active' : '' }}" 
                   @if(Route::is('student.dashboard')) onclick="event.preventDefault(); navToSection('noticeSection', this);" @endif>
                    <span class="nav-icon">📢</span> Notice Board
                </a>
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
                    <button onclick="chatWithSelected()" class="logout-btn" style="margin-bottom: 15px; background: #000; color:#fff;">💬 Chat with Selected Files</button>
                    <div class="file-list" id="fileList"></div>
                </div>

                <div id="chatSection" class="section">
                    <h2>💬 AI Chat Assistant</h2>
                    <p style="margin-bottom: 15px; color: #666;">Ask questions about your files. The AI will search through available documents.</p>
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
                    <p style="margin-bottom: 20px; color: #666;">Latest announcements and updates from the administration.</p>
                    <div id="noticeList" class="notice-grid">
                        <p style="text-align: center; color: #999;">Loading notices...</p>
                    </div>
                </div>

                <div id="resumeSection" class="section">
                    <h2>📄 Resume Advisor</h2>
                    <p style="margin-bottom: 20px; color: #666;">Upload your resume for AI analysis and improvements.</p>
                    <div class="resume-advisor">
                        <div class="drop-zone" id="resumeDropZone" onclick="document.getElementById('resumeFileInput').click()">
                            <div style="font-size: 3rem; margin-bottom: 10px;">📁</div>
                            <div style="font-weight: 700;">Click to upload your resume</div>
                            <div style="font-size: 0.85rem; color: #666; margin-top: 5px;">PDF, DOCX, TXT · Max 5 MB</div>
                        </div>
                        <input type="file" id="resumeFileInput" accept=".pdf,.docx,.txt" style="display:none;" onchange="handleResumeFile(this.files[0])">
                        
                        <div style="display: none;" id="resumeResponseBox">
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
            if(navElement) {
                document.querySelectorAll('.nav-item').forEach(n => n.classList.remove('active'));
                navElement.classList.add('active');
                
                // Close sidebar on mobile after clicking
                if(window.innerWidth <= 900) {
                    toggleSidebar();
                }

                // Load content if needed
                if (sectionId === 'noticeSection') loadNotices();

                // Update URL without reload to reflect section
                const sectionMap = { 'teacherSection': 'study', 'chatSection': 'chat', 'resumeSection': 'resume', 'noticeSection': 'notices' };
                const secParam = sectionMap[sectionId] || '';
                if (secParam) {
                    const newUrl = window.location.pathname + '?section=' + secParam;
                    window.history.pushState({path:newUrl},'',newUrl);
                }
            }
        }

        // Keep original showSection for backward compatibility with your inner buttons
        function showSection(sectionId) {
            document.querySelectorAll('.section').forEach(s => s.classList.remove('active'));
            document.getElementById(sectionId).classList.add('active');
        }

        let selectedTeacher = null;
        let selectedFolder  = null;
        let allFiles        = [];
        let activeSemester  = null;  // null = show all

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

                        const name       = t.display_name || t.username;

                        const initial    = name.charAt(0).toUpperCase();

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
                                    By: ${ (function() {
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
                                    })() }
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

            const msgDiv = document.createElement('div');

            msgDiv.className = 'message ' + type;

            msgDiv.textContent = content;

            messages.appendChild(msgDiv);

            messages.scrollTop = messages.scrollHeight;



            // Add to history

            chatHistory.push({ role: type === 'user' ? 'user' : 'assistant', content: content });

        }



        // Allow Enter key to send

        document.getElementById('chatInput').addEventListener('keypress', function(e) {

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

            const allowed = ['application/pdf', 'text/plain',

                'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

            const allowedExt = ['.pdf', '.txt', '.docx'];

            const ext = '.' + file.name.split('.').pop().toLowerCase();



            if (!allowedExt.includes(ext)) {

                alert('Unsupported file type. Please upload a PDF, DOCX or TXT file.');

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



            const btn  = document.getElementById('analyseBtn');

            const body = document.getElementById('resumeResponseBody');

            const msg  = document.getElementById('resumeMessage').value.trim();



            btn.disabled = true;

            btn.textContent = '⏳ Analysing… please wait (this may take 30–60 seconds)';

            document.getElementById('resumeResponse').style.display = 'none';



            const form = new FormData();

            form.append('resume',  resumeFile);

            form.append('message', msg || 'Please review and improve this resume.');

            form.append('_token',  '{{ csrf_token() }}');



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