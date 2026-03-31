<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - EduShare</title>
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
        }

        .navbar {
            background-color: #ffffff;
            border-bottom: 2px solid #000000;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar-title {
            font-weight: 700;
            font-size: 1.3rem;
        }

        .navbar-actions {
            display: flex;
            gap: 15px;
            align-items: center;
        }

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
        }

        .btn:hover {
            background-color: #000000;
            color: #ffffff;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        h1, h2 {
            margin-bottom: 20px;
        }

        input[type="text"] {
            width: 100%;
            padding: 12px;
            border: 2px solid #000000;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 1rem;
        }

        .teacher-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .teacher-card {
            background-color: #ffffff;
            border: 2px solid #000000;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .teacher-card:hover {
            transform: translateY(-5px);
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.2);
        }

        .teacher-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        .teacher-name {
            font-weight: 600;
            font-size: 1.1rem;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            padding: 8px 16px;
            background-color: #e0e0e0;
            border: 2px solid #000000;
            border-radius: 5px;
            text-decoration: none;
            color: #000000;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .back-link:hover {
            background-color: #000000;
            color: #ffffff;
        }

        .folder-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .folder-card {
            background-color: #ffffff;
            border: 2px solid #000000;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: 0.3s;
        }

        .folder-card:hover {
            transform: translateY(-5px);
            box-shadow: 3px 3px 0px rgba(0, 0, 0, 0.2);
        }

        .folder-icon {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .folder-name {
            font-weight: 600;
            margin-bottom: 5px;
        }

        .folder-count {
            font-size: 0.9rem;
            color: #666666;
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
            border-bottom: 1px solid #e0e0e0;
        }

        .file-item:last-child {
            border-bottom: none;
        }

        .file-name {
            font-weight: 600;
            flex-grow: 1;
        }

        .download-btn {
            padding: 6px 12px;
            background-color: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            border-radius: 5px;
            text-decoration: none;
            font-size: 0.9rem;
            cursor: pointer;
            transition: 0.3s;
        }

        .download-btn:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .empty-msg {
            text-align: center;
            color: #999999;
            padding: 40px;
        }

        .section {
            display: none;
        }

        .section.active {
            display: block;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            height: 600px;
            background-color: #ffffff;
            border: 2px solid #000000;
            border-radius: 8px;
            padding: 0;
        }

        .chat-header {
            background-color: #000000;
            color: #ffffff;
            padding: 15px;
            font-weight: 600;
            border-bottom: 2px solid #000000;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 15px;
            background-color: #f9f9f9;
        }

        .message {
            margin-bottom: 15px;
            padding: 12px;
            border-radius: 8px;
            max-width: 80%;
        }

        .message.user {
            background-color: #000000;
            color: #ffffff;
            margin-left: auto;
            text-align: right;
        }

        .message.assistant {
            background-color: #e8e8e8;
            color: #000000;
        }

        .message.system {
            background-color: #fff3cd;
            color: #000000;
            font-style: italic;
            margin: 0 auto;
        }

        .chat-input-group {
            padding: 15px;
            border-top: 2px solid #000000;
            display: flex;
            gap: 10px;
        }

        .chat-input {
            flex: 1;
            padding: 10px;
            border: 2px solid #000000;
            border-radius: 5px;
            font-size: 0.95rem;
        }

        .chat-send-btn {
            padding: 10px 20px;
            background-color: #000000;
            color: #ffffff;
            border: 2px solid #000000;
            border-radius: 5px;
            font-weight: 600;
            cursor: pointer;
            transition: 0.3s;
        }

        .chat-send-btn:hover {
            background-color: #ffffff;
            color: #000000;
        }

        .chat-send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* ── Resume Advisor ── */
        .resume-advisor {
            background: #fff;
            border: 2px solid #000;
            border-radius: 8px;
            padding: 30px;
        }

        .drop-zone {
            border: 3px dashed #000;
            border-radius: 8px;
            padding: 40px 20px;
            text-align: center;
            cursor: pointer;
            transition: .3s;
            margin-bottom: 20px;
            background: #fafafa;
        }

        .drop-zone:hover, .drop-zone.dragover {
            background: #f0f0f0;
        }

        .drop-zone-icon { font-size: 3rem; margin-bottom: 10px; }
        .drop-zone-text { font-weight: 600; font-size: 1rem; }
        .drop-zone-hint { font-size: 0.85rem; color: #666; margin-top: 5px; }

        .resume-loaded {
            display: none;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
            padding: 10px 15px;
            background: #e8f5e9;
            border: 2px solid #000;
            border-radius: 6px;
            font-weight: 600;
        }

        .resume-msg-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #000;
            border-radius: 6px;
            font-size: .95rem;
            margin-bottom: 15px;
            resize: vertical;
        }

        .analyse-btn {
            width: 100%;
            padding: 14px;
            background: #000;
            color: #fff;
            border: 2px solid #000;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: .3s;
        }

        .analyse-btn:hover:not(:disabled) { background: #333; }
        .analyse-btn:disabled { opacity: .5; cursor: not-allowed; }

        .resume-response {
            display: none;
            margin-top: 25px;
            border-top: 2px solid #000;
            padding-top: 20px;
        }

        .resume-response h3 { margin-bottom: 15px; }

        .resume-response-body {
            background: #f9f9f9;
            border: 2px solid #000;
            border-radius: 6px;
            padding: 20px;
            white-space: pre-wrap;
            line-height: 1.7;
            font-size: .95rem;
            max-height: 550px;
            overflow-y: auto;
        }

        .copy-resume-btn {
            margin-top: 12px;
            padding: 8px 20px;
            border: 2px solid #000;
            background: #fff;
            font-weight: 600;
            border-radius: 5px;
            cursor: pointer;
            transition: .3s;
        }

        .copy-resume-btn:hover { background: #000; color: #fff; }

        @media (max-width: 768px) {
            .teacher-list,
            .folder-grid {
                grid-template-columns: 1fr;
            }
            
            .chat-container {
                height: 400px;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">🎓 Student Dashboard</div>
        <div class="navbar-actions">
            <button onclick="showSection('chatSection')" class="btn">💬 Chat</button>
            <button onclick="showSection('resumeSection')" class="btn">📄 Resume Advisor</button>
            <span>Welcome, {{ Auth::user()->username }}!</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <!-- Teachers Section -->
        <div id="teacherSection" class="section active">
            <h1>📚 Select a Teacher</h1>
            <input type="text" id="searchTeacher" placeholder="Search Teacher..." />
            <div class="teacher-list" id="teacherList">
                <p style="text-align: center; grid-column: 1/-1;">Loading teachers...</p>
            </div>
        </div>

        <!-- Folders Section -->
        <div id="folderSection" class="section">
            <button onclick="showTeachers()" class="back-link">⬅ Back to Teachers</button>
            <h2 id="folderTitle">Folders</h2>
            <div class="folder-grid" id="folderGrid">
                <p style="text-align: center; grid-column: 1/-1;">Loading folders...</p>
            </div>
        </div>

        <!-- Files Section -->
        <div id="fileSection" class="section">
            <button onclick="showFolders()" class="back-link">⬅ Back to Folders</button>
            <h2 id="fileTitle">Files</h2>
            <input type="text" id="searchFiles" placeholder="Search Files..." />
            <button onclick="chatWithSelected()" class="btn" style="margin-bottom: 15px;">💬 Chat with Selected Files</button>
            <div class="file-list" id="fileList">
                <p style="text-align: center;">Loading files...</p>
            </div>
        </div>

        <!-- Chat Section -->
        <div id="chatSection" class="section">
            <button onclick="showTeachers()" class="back-link">⬅ Back to Teachers</button>
            <h2>💬 AI Chat Assistant</h2>
            <p style="margin-bottom: 15px; color: #666;">Ask questions about your files. The AI will search through available documents to help answer your queries.</p>
            <div class="chat-container">
                <div class="chat-header">Chat with AI Assistant</div>
                <div class="chat-messages" id="chatMessages"></div>
                <div class="chat-input-group">
                    <input type="text" id="chatInput" class="chat-input" placeholder="Type your question here..." />
                    <button onclick="sendChat()" class="chat-send-btn" id="chatSendBtn">Send</button>
                </div>
            </div>
        </div>

        <!-- Resume Advisor Section -->
        <div id="resumeSection" class="section">
            <button onclick="showTeachers()" class="back-link">⬅ Back to Teachers</button>
            <h2>📄 Resume Advisor</h2>
            <p style="margin-bottom: 20px; color: #666;">Upload your resume and the AI will analyse it and provide a fully improved version with suggestions.</p>

            <div class="resume-advisor">
                <!-- Drop zone -->
                <div class="drop-zone" id="resumeDropZone" onclick="document.getElementById('resumeFileInput').click()">
                    <div class="drop-zone-icon">📁</div>
                    <div class="drop-zone-text">Click to upload your resume</div>
                    <div class="drop-zone-hint">Supports PDF, DOCX, TXT &nbsp;·&nbsp; Max 5 MB</div>
                </div>
                <input type="file" id="resumeFileInput" accept=".pdf,.docx,.txt" style="display:none;" onchange="handleResumeFile(this.files[0])">

                <!-- Loaded indicator -->
                <div class="resume-loaded" id="resumeLoaded">
                    <span style="font-size:1.3rem;">✅</span>
                    <span id="resumeLoadedName"></span>
                    <button onclick="clearResume()" style="margin-left:auto;padding:4px 10px;border:2px solid #000;border-radius:4px;cursor:pointer;background:#fff;font-weight:600;">✕ Remove</button>
                </div>

                <!-- Custom instruction -->
                <textarea id="resumeMessage" class="resume-msg-input" rows="3"
                    placeholder="Optional: tell the AI what to focus on, e.g. 'I am applying for a software engineer role'">Please review and improve this resume.</textarea>

                <!-- Analyse button -->
                <button id="analyseBtn" class="analyse-btn" onclick="analyseResume()" disabled>⚡ Analyse Resume</button>

                <!-- Response area -->
                <div class="resume-response" id="resumeResponse">
                    <h3>🤖 AI Feedback &amp; Improved Resume</h3>
                    <div class="resume-response-body" id="resumeResponseBody"></div>
                    <button class="copy-resume-btn" onclick="copyResumeResponse()">📋 Copy to Clipboard</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        let selectedTeacher = null;
        let selectedFolder = null;
        let allFiles = [];

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
            fetch('{{ route("student.teachers") }}')
                .then(r => r.json())
                .then(teachers => {
                    const list = document.getElementById('teacherList');
                    list.innerHTML = teachers.map(t => `
                        <div class="teacher-card" onclick="selectTeacher('${t.username}')">
                            <div class="teacher-icon">👨‍🏫</div>
                            <div class="teacher-name">${t.username}</div>
                        </div>
                    `).join('');
                });
        }

        function selectTeacher(teacherName) {
            selectedTeacher = teacherName;
            document.getElementById('folderTitle').textContent = `${teacherName}'s Folders`;
            loadFolders();
            showSection('folderSection');
        }

        function loadFolders() {
            fetch(`{{ route("student.files") }}?action=folders&teacher=${selectedTeacher}`)
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
            fetch(`{{ route("student.files") }}?action=files&teacher=${selectedTeacher}&folder=${selectedFolder}`)
                .then(r => r.json())
                .then(files => {
                    allFiles = files;
                    renderFiles(files);
                });
        }

        function renderFiles(files) {
            const list = document.getElementById('fileList');
            if (files.length === 0) {
                list.innerHTML = '<p class="empty-msg">No files in this folder.</p>';
                return;
            }
            list.innerHTML = files.map(f => `
                <div class="file-item">
                    <input type="checkbox" class="file-checkbox" value="${f.filename}" style="margin-right: 10px;">
                    <div class="file-name">📄 ${f.filename}</div>
                    <a href="/drive-in-laravel/uploads/{{ preg_replace('/[^a-zA-Z0-9_]/', '', Auth::user()->username) }}/download" class="download-btn">Download</a>
                </div>
            `).join('');
        }

        function chatWithSelected() {
            selectedFiles = Array.from(document.querySelectorAll('.file-checkbox:checked')).map(cb => cb.value);
            if (selectedFiles.length === 0) {
                alert('Please select at least one file to chat with.');
                return;
            }
            showSection('chatSection');
        }

        // Initialize
        loadTeachers();

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
    </script>
</body>
</html>
