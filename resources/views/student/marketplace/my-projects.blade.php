<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Projects – Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @include('student.marketplace._styles')
</head>
<body style="background:#f8faff;min-height:100vh;">

<div class="mp-wrap">

  @include('student.marketplace._nav')

  <div class="mp-page-header">
    <div class="mp-page-title">My Projects 📂</div>
    <div class="mp-page-sub">Track your project journey — {{ count($started) }} in progress, {{ count($completed) }} completed</div>
  </div>

  {{-- Stats --}}
  @if(count($completed) > 0)
  <div style="padding:16px 20px;">
    <div style="display:flex;gap:10px;">
      <div class="mp-stat">
        <div class="mp-stat-num" style="color:#22c55e;">{{ $stats['Normal'] }}</div>
        <div class="mp-stat-label">🟢 Normal Completed</div>
      </div>
      <div class="mp-stat">
        <div class="mp-stat-num" style="color:#f59e0b;">{{ $stats['Good'] }}</div>
        <div class="mp-stat-label">🟡 Good Completed</div>
      </div>
      <div class="mp-stat">
        <div class="mp-stat-num" style="color:#ef4444;">{{ $stats['Stretch'] }}</div>
        <div class="mp-stat-label">🔴 Stretch Completed</div>
      </div>
    </div>
  </div>
  @endif

  {{-- In Progress --}}
  @if(count($started) > 0)
    <div class="mp-section-label" style="padding-top:16px;">In Progress ⏳</div>
    <div style="padding:0 20px;display:flex;flex-direction:column;gap:10px;">
      @foreach($started as $project)
        @php $diffClass = ['Normal'=>'mp-badge-normal','Good'=>'mp-badge-good','Stretch'=>'mp-badge-stretch'][$project['difficulty']] ?? 'mp-badge-skill'; @endphp
        <div class="mp-card" style="cursor:default;">
          <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:8px;">
            <div class="mp-card-title">{{ $project['title'] }}</div>
            <span class="mp-badge {{ $diffClass }}">{{ $project['difficulty'] }}</span>
          </div>
          <div style="font-size:12px;color:#94a3b8;margin-bottom:10px;">⏱ {{ $project['duration'] }}</div>
          <div style="display:flex;gap:8px;flex-wrap:wrap;">
            <form method="POST" action="{{ route('marketplace.projects.status', $project['id']) }}">
              @csrf
              <input type="hidden" name="status" value="completed">
              <button type="submit" class="mp-btn-done">✅ Mark Complete</button>
            </form>
            <form method="POST" action="{{ route('marketplace.projects.status', $project['id']) }}">
              @csrf
              <input type="hidden" name="status" value="remove">
              <button type="submit" class="mp-btn-ghost" style="width:auto;padding:7px 14px;font-size:12px;">Remove</button>
            </form>
          </div>
        </div>
      @endforeach
    </div>
  @endif

  {{-- Completed --}}
  @if(count($completed) > 0)
    <div class="mp-section-label" style="padding-top:20px;">Completed 🎉</div>
    <div style="padding:0 20px;display:flex;flex-direction:column;gap:10px;padding-bottom:30px;">
      @foreach($completed as $project)
        @php $diffClass = ['Normal'=>'mp-badge-normal','Good'=>'mp-badge-good','Stretch'=>'mp-badge-stretch'][$project['difficulty']] ?? 'mp-badge-skill'; @endphp
        <div class="mp-card" style="cursor:default;border-color:rgba(34,197,94,.3);background:rgba(34,197,94,.03);">
          <div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:6px;">
            <div class="mp-card-title">{{ $project['title'] }}</div>
            <span class="mp-badge mp-badge-green">✅ Done</span>
          </div>
          <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:10px;">
            <span class="mp-badge {{ $diffClass }}">{{ $project['difficulty'] }}</span>
            <span class="mp-badge mp-badge-skill">⏱ {{ $project['duration'] }}</span>
          </div>
          <form method="POST" action="{{ route('marketplace.projects.status', $project['id']) }}" style="display:inline;">
            @csrf
            <input type="hidden" name="status" value="remove">
            <button type="submit" class="mp-btn-ghost" style="width:auto;padding:6px 14px;font-size:11px;">Remove</button>
          </form>
        </div>
      @endforeach
    </div>
  @endif

  {{-- Empty state --}}
  @if(count($started) === 0 && count($completed) === 0)
    <div class="mp-empty">
      <div class="mp-empty-icon">📂</div>
      <div class="mp-empty-title">No projects yet</div>
      <div class="mp-empty-sub">Go to the Project Feed and start your first project.</div>
      <a href="{{ route('marketplace.feed') }}" style="display:inline-block;margin-top:16px;padding:10px 22px;background:#6366f1;color:#fff;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;">Browse Projects →</a>
    </div>
  @endif

</div>
</body>
</html>
