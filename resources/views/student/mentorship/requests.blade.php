<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Mentorship Requests - EduShare</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #CCD0CF; padding: 40px 20px; color: #06141B; line-height: 1.5; }
        .container { max-width: 900px; margin: 0 auto; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .header h1 { font-size: 2rem; font-weight: 800; letter-spacing: -1px; }
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
        .card { 
            background: #ffffff; 
            border: 1px solid rgba(6, 20, 27, 0.05); 
            border-radius: 24px; 
            padding: 40px; 
            box-shadow: 0 4px 20px rgba(6, 20, 27, 0.04); 
        }
        .request-item { 
            border-bottom: 1px solid rgba(6, 20, 27, 0.05); 
            padding: 25px 0; 
            display: flex; 
            justify-content: space-between; 
            align-items: center; 
        }
        .request-item:last-child { border-bottom: none; }
        .request-item h3 { font-size: 1.2rem; font-weight: 800; color: #06141B; }
        .status-badge { 
            padding: 6px 16px; 
            border-radius: 20px; 
            font-size: 0.75rem; 
            font-weight: 800; 
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .status-pending { background: #FEF3C7; color: #92400E; }
        .status-accepted { background: #D1FAE5; color: #065F46; }
        .status-declined { background: #FEE2E2; color: #991B1B; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📩 My Mentorship Requests</h1>
            <a href="{{ route('mentorship.browse') }}" class="back-link">Back to Browse</a>
        </div>

        <div class="card">
            @forelse($requests as $request)
                <div class="request-item">
                    <div>
                        <h3 style="margin-bottom: 5px;">To: {{ $request->alumni->username }}</h3>
                        <p style="color: #666; font-size: 0.9rem;">Sent: {{ $request->created_at->format('M d, Y') }}</p>
                        <p style="margin-top: 10px; font-style: italic;">"{{ Str::limit($request->message, 100) }}"</p>
                    </div>
                    <div class="status-badge status-{{ $request->status }}">
                        {{ ucfirst($request->status) }}
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #666; padding: 30px;">You haven't sent any requests yet.</p>
            @endforelse
        </div>
    </div>
</body>
</html>
