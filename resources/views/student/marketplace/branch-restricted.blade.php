<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Feature Not Available – Project Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      font-family: 'Inter', sans-serif;
      background: #f8faff;
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      padding: 24px;
    }
    .card {
      background: #fff;
      border: 1.5px solid #e2e8f0;
      border-radius: 20px;
      padding: 40px 32px;
      max-width: 420px;
      width: 100%;
      text-align: center;
      box-shadow: 0 4px 30px rgba(99,102,241,.08);
    }
    .emoji { font-size: 56px; margin-bottom: 20px; }
    h1 { font-size: 22px; font-weight: 800; color: #1e293b; margin-bottom: 10px; }
    p { font-size: 14px; color: #64748b; line-height: 1.7; margin-bottom: 8px; }
    .branch-badge {
      display: inline-block;
      background: rgba(100,116,139,.1);
      color: #64748b;
      border: 1px solid rgba(100,116,139,.2);
      border-radius: 999px;
      padding: 4px 14px;
      font-size: 13px;
      font-weight: 600;
      margin: 14px 0 24px;
    }
    .info-box {
      background: rgba(99,102,241,.06);
      border: 1px solid rgba(99,102,241,.18);
      border-radius: 10px;
      padding: 12px 16px;
      font-size: 13px;
      color: #4f46e5;
      margin-bottom: 28px;
      text-align: left;
    }
    .back-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 12px 28px;
      background: #1e293b;
      color: #fff;
      border-radius: 10px;
      font-size: 14px;
      font-weight: 700;
      text-decoration: none;
      transition: opacity .15s;
    }
    .back-btn:hover { opacity: .85; }
  </style>
</head>
<body>
  <div class="card">
    <div class="emoji">🔒</div>
    <h1>Feature Not Available</h1>
    <p>The <strong>CS/IT Project & Internship Marketplace</strong> is designed specifically for Computer Science and Electronics branch students.</p>
    <div class="branch-badge">Your branch: {{ $branch }}</div>
    <div class="info-box">
      💡 This feature contains CS/IT project ideas, placement-focused resources, and internship listings tailored for CS & Electronics students.
    </div>
    <a href="{{ route('student.dashboard') }}" class="back-btn">
      ← Back to Dashboard
    </a>
  </div>
</body>
</html>
