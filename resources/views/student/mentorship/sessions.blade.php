<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Mentorship Sessions - EduShare</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #CCD0CF; padding: 40px 20px; color: #06141B; line-height: 1.5; }
        .container { max-width: 1000px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .header h1 { font-size: 2.2rem; font-weight: 800; letter-spacing: -1px; }
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
        }
        .back-link:hover { background: #F2F4F3; color: #06141B; transform: translateX(-4px); }
        .sessions-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px; }
        .session-card { 
            background: #ffffff; 
            border: 1px solid rgba(6, 20, 27, 0.05); 
            border-radius: 24px; 
            padding: 35px; 
            box-shadow: 0 4px 20px rgba(6, 20, 27, 0.04); 
            transition: all 0.3s ease;
        }
        .session-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(6, 20, 27, 0.08); }
        .btn { 
            display: inline-block; 
            padding: 14px 24px; 
            background: #253745; 
            color: #CCD0CF; 
            text-decoration: none; 
            border-radius: 12px; 
            font-weight: 700; 
            text-align: center; 
            border: none; 
            margin-top: 25px; 
            width: 100%; 
            transition: all 0.3s;
            box-shadow: 0 4px 12px rgba(37, 55, 69, 0.1);
        }
        .btn:hover { background: #1a2833; transform: translateY(-1px); box-shadow: 0 6px 15px rgba(37, 55, 69, 0.2); }
        .badge { 
            padding: 6px 16px; 
            border-radius: 20px; 
            font-size: 0.75rem; 
            font-weight: 800; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 15px; 
            display: inline-block;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📅 My Mentorship Sessions</h1>
            <a href="{{ route('mentorship.browse') }}" class="back-link">Back to Browse</a>
        </div>

        <div class="sessions-grid">
            @forelse($sessions as $session)
                <div class="session-card">
                    <div class="badge" style="background: #dbeafe; color: #1e40af;">{{ ucfirst($session->status) }}</div>
                    <h3 style="margin-bottom: 10px;">{{ $session->title }}</h3>
                    <p style="margin-bottom: 5px;">Alumni: <strong>{{ $session->alumni->username }}</strong></p>
                    <p style="color: #666; font-size: 0.9rem;">Scheduled for: {{ $session->scheduled_at->format('M d, Y - h:i A') }}</p>
                    <a href="{{ route('mentorship.session.chat', $session->id) }}" class="btn">Join Session Chat</a>
                </div>
            @empty
                <p style="grid-column: 1/-1; text-align: center; color: #666; padding: 50px;">No sessions scheduled yet.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
