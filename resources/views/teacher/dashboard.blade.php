<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher Dashboard - EduShare</title>
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

        h1 {
            font-size: 2rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 30px;
            margin-bottom: 40px;
        }

        .profile-card {
            background-color: #ffffff;
            border: 2px solid #000000;
            padding: 30px;
            border-radius: 8px;
            text-align: center;
        }

        .profile-image {
            width: 150px;
            height: 150px;
            background-color: #e0e0e0;
            border: 2px solid #000000;
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .profile-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
            text-align: left;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 2px solid #000000;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .upload-section {
            background-color: #ffffff;
            border: 2px solid #000000;
            padding: 30px;
            border-radius: 8px;
        }

        .upload-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 20px;
        }

        .files-list {
            background-color: #f9f9f9;
            border: 2px solid #000000;
            border-radius: 8px;
            padding: 20px;
            margin-top: 30px;
        }

        .file-item {
            background-color: #ffffff;
            border: 1px solid #cccccc;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .file-name {
            font-weight: 600;
        }

        .file-date {
            color: #666666;
            font-size: 0.9rem;
        }

        .alert {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #e8f5e9;
            border: 2px solid #2e7d32;
            color: #2e7d32;
        }

        .alert-error {
            background-color: #ffebee;
            border: 2px solid #c62828;
            color: #c62828;
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="navbar-title">📚 Teacher Dashboard</div>
        <div class="navbar-actions">
            <span>Welcome, {{ Auth::user()->username }}!</span>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-error">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <h1>Your Teaching Hub</h1>

        <div class="content-grid">
            <!-- Profile Section -->
            <div class="profile-card">
                <div class="profile-image">
                    <img src="/uploads/{{ preg_replace('/[^a-zA-Z0-9_]/', '', Auth::user()->username) }}/profile.jpg" alt="Profile" onerror="this.style.display='none'">
                </div>
                <div class="profile-name" id="displayName">{{ Auth::user()->username }}</div>

                <form method="POST" action="{{ route('teacher.updateName') }}" style="margin-bottom: 20px;">
                    @csrf
                    <div class="form-group">
                        <label>Update Display Name</label>
                        <input type="text" name="name" placeholder="Enter new name">
                        <button type="submit" class="btn">Update Name</button>
                    </div>
                </form>

                <form method="POST" action="{{ route('teacher.updateImage') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Update Profile Picture</label>
                        <input type="file" name="profileImage" accept="image/*">
                        <button type="submit" class="btn">Update Image</button>
                    </div>
                </form>
            </div>

            <!-- Upload Section -->
            <div class="upload-section">
                <div class="upload-title">📤 Upload Files</div>
                <form method="POST" action="{{ route('teacher.upload') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Select File (Max 10MB)</label>
                        <input type="file" name="file" required>
                        <small>Supported: PDF, DOCX, XLSX, PPTX, TXT, PNG, JPG, MP3, MP4, ZIP, etc.</small>
                    </div>
                    <button type="submit" class="btn" style="width: 100%; padding: 12px;">Upload File</button>
                </form>
            </div>
        </div>

        <!-- Files List -->
        <div class="files-list">
            <h2 style="margin-bottom: 20px;">📁 Your Uploaded Files</h2>
            <div id="filesList">
                <p style="text-align: center; color: #999;">Loading files...</p>
            </div>
        </div>
    </div>

    <script>
        // Load files via AJAX
        function loadFiles() {
            fetch('{{ route("teacher.files") }}')
                .then(response => response.json())
                .then(files => {
                    const filesList = document.getElementById('filesList');
                    if (files.length === 0) {
                        filesList.innerHTML = '<p style="text-align: center; color: #999;">No files uploaded yet.</p>';
                        return;
                    }
                    filesList.innerHTML = files.map(file => `
                        <div class="file-item">
                            <div>
                                <div class="file-name">📄 ${file.filename}</div>
                                <div class="file-date">${new Date(file.uploaded_at).toLocaleDateString()}</div>
                            </div>
                        </div>
                    `).join('');
                });
        }

        loadFiles();
    </script>
</body>
</html>
