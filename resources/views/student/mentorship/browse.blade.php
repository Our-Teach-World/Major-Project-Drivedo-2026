<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Alumni - EduShare</title>
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
            padding: 40px 20px;
            line-height: 1.5;
        }

        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: -1px;
            color: #06141B;
        }

        .back-link {
            text-decoration: none;
            color: #4A5568;
            font-weight: 700;
            border: 1px solid rgba(6, 20, 27, 0.1);
            background: #ffffff;
            padding: 10px 20px;
            border-radius: 12px;
            transition: all 0.2s;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .back-link:hover {
            background: #F2F4F3;
            color: #06141B;
            transform: translateX(-4px);
        }

        .search-bar {
            margin-bottom: 40px;
        }

        .search-input {
            width: 100%;
            padding: 16px 20px;
            border: 1px solid rgba(6, 20, 27, 0.1);
            border-radius: 16px;
            font-size: 1rem;
            background: #ffffff;
            transition: all 0.2s;
            color: #06141B;
        }

        .search-input:focus {
            outline: none;
            border-color: #253745;
            box-shadow: 0 0 0 4px rgba(37, 55, 69, 0.1);
        }

        .alumni-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }

        .alumni-card {
            background: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            border-radius: 24px;
            padding: 35px;
            box-shadow: 0 4px 20px rgba(6, 20, 27, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex;
            flex-direction: column;
        }

        .alumni-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(6, 20, 27, 0.1);
            border-color: rgba(37, 55, 69, 0.1);
        }

        .avatar {
            width: 70px;
            height: 70px;
            background: #F2F4F3;
            color: #253745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 20px;
            border: 1px solid rgba(6, 20, 27, 0.05);
        }

        .name {
            font-size: 1.3rem;
            font-weight: 800;
            margin-bottom: 6px;
            color: #06141B;
            letter-spacing: -0.5px;
        }

        .company {
            color: #253745;
            font-size: 0.9rem;
            margin-bottom: 18px;
            font-weight: 700;
            background: rgba(37, 55, 69, 0.05);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
            width: fit-content;
        }

        .bio {
            font-size: 0.95rem;
            line-height: 1.6;
            color: #4A5568;
            margin-bottom: 25px;
            flex: 1;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #253745;
            color: #CCD0CF;
            text-decoration: none;
            border: none;
            border-radius: 12px;
            font-weight: 700;
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(37, 55, 69, 0.1);
        }

        .btn:hover {
            background: #1a2833;
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(37, 55, 69, 0.2);
        }

        .btn-outline {
            background: #ffffff;
            color: #253745;
            border: 1px solid rgba(37, 55, 69, 0.1);
            box-shadow: none;
        }

        .btn-outline:hover {
            background: #F2F4F3;
            box-shadow: 0 4px 12px rgba(6, 20, 27, 0.05);
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(6, 20, 27, 0.3);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .modal-content {
            background: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            padding: 40px;
            border-radius: 24px;
            width: 90%;
            max-width: 550px;
            box-shadow: 0 25px 60px rgba(6, 20, 27, 0.15);
            animation: slideUp 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-title {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 25px;
            color: #06141B;
            letter-spacing: -0.8px;
        }

        textarea {
            width: 100%;
            padding: 16px;
            border: 1px solid rgba(6, 20, 27, 0.1);
            border-radius: 16px;
            font-size: 1rem;
            background: #F8F9F9;
            color: #06141B;
            font-family: inherit;
            transition: all 0.2s;
            resize: none;
        }

        textarea:focus {
            outline: none;
            border-color: #253745;
            box-shadow: 0 0 0 4px rgba(37, 55, 69, 0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🤝 Connect with Alumni</h1>
            <a href="{{ route('student.dashboard') }}" class="back-link">
                <span>←</span> Back to Dashboard
            </a>
        </div>

        <div style="display: flex; gap: 15px; margin-bottom: 40px;">
            <a href="{{ route('mentorship.requests') }}" class="btn btn-outline">My Requests</a>
            <a href="{{ route('mentorship.sessions') }}" class="btn btn-outline">My Sessions</a>
        </div>

        @if(session('success'))
            <div style="background: #F0FDF4; color: #166534; padding: 20px; border: 1px solid #BBF7D0; border-radius: 16px; margin-bottom: 30px; font-weight: 700; display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 1.2rem;">✓</span>
                {{ session('success') }}
            </div>
        @endif

        <div class="search-bar">
            <form action="{{ route('mentorship.browse') }}" method="GET">
                <input type="text" name="search" class="search-input" placeholder="Search by name, company, or expertise..." value="{{ request('search') }}">
            </form>
        </div>

        <div class="alumni-grid">
            @forelse($alumni as $alum)
                <div class="alumni-card">
                    <div class="avatar">{{ strtoupper(substr($alum->username, 0, 1)) }}</div>
                    <div class="name">{{ $alum->username }}</div>
                    <div class="company">{{ $alum->company ?? 'Independent Professional' }}</div>
                    <div class="bio">{{ Str::limit($alum->bio, 120) }}</div>
                    <button class="btn" onclick="openRequestModal('{{ $alum->id }}', '{{ $alum->username }}')">Request Mentorship</button>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: #4A5568; padding: 80px 20px; background: #ffffff; border-radius: 24px; border: 1px solid rgba(6, 20, 27, 0.05);">
                    <p style="font-size: 1.2rem; font-weight: 700; margin-bottom: 10px;">No alumni found</p>
                    <p>Try searching for something else or browse all alumni.</p>
                </div>
            @endforelse
        </div>

        <div style="margin-top: 50px;">
            {{ $alumni->links() }}
        </div>
    </div>

    <!-- Request Modal -->
    <div id="requestModal" class="modal">
        <div class="modal-content">
            <h2 id="modalTitle" class="modal-title">Request Mentorship</h2>
            <form action="{{ route('mentorship.request') }}" method="POST">
                @csrf
                <input type="hidden" id="alumni_id" name="alumni_id">
                <div style="margin-bottom: 25px;">
                    <label style="display: block; margin-bottom: 12px; font-weight: 800; color: #253745; font-size: 0.9rem;">WHY DO YOU WANT MENTORSHIP?</label>
                    <textarea name="message" rows="5" placeholder="Introduce yourself and explain your goals..." required></textarea>
                </div>
                <div style="display: flex; gap: 15px; justify-content: flex-end;">
                    <button type="button" class="btn btn-outline" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn">Send Request</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRequestModal(id, name) {
            document.getElementById('alumni_id').value = id;
            document.getElementById('modalTitle').innerText = 'Request Mentorship from ' + name;
            document.getElementById('requestModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('requestModal').style.display = 'none';
        }

        // Close on outside click
        window.onclick = function(event) {
            if (event.target == document.getElementById('requestModal')) {
                closeModal();
            }
        }
    </script>
</body>
</html>
