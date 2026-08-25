<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Project Feed – Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @include('student.marketplace._styles')
</head>
<body style="background:#f8faff;min-height:100vh;">

<div class="mp-wrap">

  @include('student.marketplace._nav')

  {{-- Page Header --}}
  <div class="mp-page-header">
    <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:4px;">
      <div class="mp-page-title">Project Feed 🚀</div>
      <a href="{{ route('student.dashboard') }}" style="font-size:12px;color:#94a3b8;text-decoration:none;flex-shrink:0;">← Dashboard</a>
    </div>
    <div class="mp-page-sub">
      Hi <strong>{{ $studentInfo['display_name'] }}</strong> · {{ $studentInfo['branch'] }} · {{ $studentInfo['semester_label'] }} (Year {{ $studentInfo['year'] }}) · {{ count($projects) }} project{{ count($projects) !== 1 ? 's' : '' }} found
    </div>
  </div>

  {{-- Flash --}}
  @if(session('success'))
    <div class="mp-info" style="margin:12px 20px;">✅ {{ session('success') }}</div>
  @endif

  {{-- Filters --}}
  <div style="padding:12px 20px 0;background:#fff;border-bottom:1px solid #e2e8f0;">
    <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:12px;scrollbar-width:none;">
      @foreach(['All','Normal','Good','Stretch'] as $diff)
        <a href="{{ request()->fullUrlWithQuery(['difficulty' => $diff]) }}"
           class="mp-pill {{ $filterDifficulty === $diff ? 'active' : '' }}">
          {{ ['All'=>'🔍 All','Normal'=>'🟢 Normal','Good'=>'🟡 Good','Stretch'=>'🔴 Stretch'][$diff] }}
        </a>
      @endforeach
      <span style="width:1px;background:#e2e8f0;margin:0 4px;"></span>
      <a href="{{ request()->fullUrlWithQuery(['sort' => 'recommended']) }}"
         class="mp-pill {{ $sortBy === 'recommended' ? 'active' : '' }}">⭐ Recommended</a>
      <a href="{{ request()->fullUrlWithQuery(['sort' => 'placement']) }}"
         class="mp-pill {{ $sortBy === 'placement' ? 'active' : '' }}">🏢 Placement</a>
    </div>
  </div>

  {{-- Project Cards --}}
  <div style="padding:16px 20px;display:flex;flex-direction:column;gap:12px;">

    @forelse($projects as $project)
      @php
        $status     = $myProjects[$project['id']] ?? null;
        $diffClass  = ['Normal'=>'mp-badge-normal','Good'=>'mp-badge-good','Stretch'=>'mp-badge-stretch'][$project['difficulty']] ?? 'mp-badge-skill';
      @endphp

      <div class="mp-card" onclick="openModal('modal-{{ $project['id'] }}')" id="card-{{ $project['id'] }}">
        {{-- Top row --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:8px;">
          <div style="flex:1;">
            <div class="mp-card-title">{{ $project['title'] }}</div>
            @if($status === 'started')
              <span class="mp-badge mp-badge-primary" style="margin-bottom:6px;">⏳ In Progress</span>
            @elseif($status === 'completed')
              <span class="mp-badge mp-badge-green" style="margin-bottom:6px;">✅ Completed</span>
            @endif
          </div>
          <div style="flex-shrink:0;text-align:right;">
            <span class="mp-badge {{ $diffClass }}">{{ $project['difficulty'] }}</span>
            <div style="font-size:11px;color:#94a3b8;margin-top:4px;">⏱ {{ $project['duration'] }}</div>
          </div>
        </div>

        {{-- Why excerpt --}}
        <p style="font-size:13px;color:#64748b;line-height:1.6;margin-bottom:10px;">
          {{ Str::limit($project['why'], 100) }}
        </p>

        {{-- Skill badges --}}
        <div style="display:flex;flex-wrap:wrap;gap:5px;margin-bottom:12px;">
          @foreach($project['skillsRequired'] as $skill)
            <span class="mp-badge mp-badge-skill">{{ $skill }}</span>
          @endforeach
          @if($project['placementRelevant'])
            <span class="mp-badge mp-badge-green">🏢 Placement</span>
          @endif
          @if($project['lowEndLaptop'])
            <span class="mp-badge mp-badge-cert">💻 Low-end OK</span>
          @endif
        </div>

        {{-- Actions (stop propagation so card click doesn't open modal) --}}
        <div style="display:flex;gap:8px;" onclick="event.stopPropagation()">
          @if(!$status)
            <form method="POST" action="{{ route('marketplace.projects.status', $project['id']) }}">
              @csrf
              <input type="hidden" name="status" value="started">
              <button type="submit" class="mp-btn-start">▶ Start</button>
            </form>
          @elseif($status === 'started')
            <form method="POST" action="{{ route('marketplace.projects.status', $project['id']) }}">
              @csrf
              <input type="hidden" name="status" value="completed">
              <button type="submit" class="mp-btn-done">✅ Mark Done</button>
            </form>
            <form method="POST" action="{{ route('marketplace.projects.status', $project['id']) }}">
              @csrf
              <input type="hidden" name="status" value="remove">
              <button type="submit" class="mp-btn-ghost" style="width:auto;padding:7px 14px;font-size:12px;">Remove</button>
            </form>
          @elseif($status === 'completed')
            <form method="POST" action="{{ route('marketplace.projects.status', $project['id']) }}">
              @csrf
              <input type="hidden" name="status" value="remove">
              <button type="submit" class="mp-btn-ghost" style="width:auto;padding:7px 14px;font-size:12px;">✕ Remove</button>
            </form>
          @endif
          <button type="button" onclick="openModal('modal-{{ $project['id'] }}')" class="mp-btn-ghost" style="width:auto;padding:7px 14px;font-size:12px;">
            View Details →
          </button>
        </div>
      </div>

      {{-- Bottom-sheet Modal --}}
      <div class="mp-overlay" id="overlay-{{ $project['id'] }}" onclick="closeModal('modal-{{ $project['id'] }}')"></div>
      <div class="mp-sheet" id="modal-{{ $project['id'] }}">
        <div class="mp-sheet-handle"><span></span></div>
        <div style="padding:16px 20px 40px;">

          {{-- Title + badges --}}
          <div style="margin-bottom:16px;">
            <div style="font-size:20px;font-weight:800;color:#1e293b;margin-bottom:8px;">{{ $project['title'] }}</div>
            <div style="display:flex;flex-wrap:wrap;gap:6px;">
              <span class="mp-badge {{ $diffClass }}">{{ $project['difficulty'] }}</span>
              <span class="mp-badge mp-badge-skill">⏱ {{ $project['duration'] }}</span>
              @if($project['teamProject'])<span class="mp-badge mp-badge-cert">👥 Team</span>@endif
              @if($project['lowEndLaptop'])<span class="mp-badge mp-badge-cert">💻 Low-end OK</span>@endif
              @if($project['placementRelevant'])<span class="mp-badge mp-badge-green">🏢 Placement</span>@endif
            </div>
          </div>

          {{-- Tags --}}
          @if($project['tags'])
            <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;">
              @foreach($project['tags'] as $tag)
                <span class="mp-badge mp-badge-primary">{{ $tag }}</span>
              @endforeach
            </div>
          @endif

          {{-- Why --}}
          <div class="mp-modal-section-head">💡 Why build this?</div>
          <p style="font-size:13px;color:#374151;line-height:1.75;margin-bottom:4px;">{{ $project['why'] }}</p>

          {{-- What --}}
          <div class="mp-modal-section-head">🎯 What you'll build</div>
          <p style="font-size:13px;color:#374151;line-height:1.75;margin-bottom:4px;">{{ $project['what'] }}</p>

          {{-- How --}}
          <div class="mp-modal-section-head">🛠 How to build it</div>
          @foreach($project['how'] as $i => $step)
            <div class="mp-step">
              <div class="mp-step-num">{{ $i + 1 }}</div>
              <p>{{ $step }}</p>
            </div>
          @endforeach

          {{-- Skills you'll learn --}}
          <div class="mp-modal-section-head">📚 Skills you'll learn</div>
          <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:4px;">
            @foreach($project['skillsYouWillLearn'] as $learn)
              <span class="mp-badge mp-badge-primary">{{ $learn }}</span>
            @endforeach
          </div>

          {{-- Tools --}}
          <div class="mp-modal-section-head">🔧 Tools needed</div>
          <div class="mp-info" style="margin-bottom:8px;">{{ $project['toolsNeeded'] }}</div>

          {{-- Resources --}}
          @if($project['where'])
            <div class="mp-modal-section-head">🎬 Learning Resources</div>
            @foreach($project['where'] as $res)
              <a href="{{ $res['link'] }}" target="_blank" class="mp-resource-link">
                🔗 {{ $res['name'] }}
              </a>
            @endforeach
          @endif

          {{-- Action buttons --}}
          <div style="margin-top:20px;display:flex;flex-direction:column;gap:8px;" onclick="event.stopPropagation()">
            @if(!($myProjects[$project['id']] ?? null))
              <form method="POST" action="{{ route('marketplace.projects.status', $project['id']) }}">
                @csrf
                <input type="hidden" name="status" value="started">
                <button type="submit" class="mp-btn-primary">▶ Start This Project</button>
              </form>
            @elseif(($myProjects[$project['id']] ?? null) === 'started')
              <form method="POST" action="{{ route('marketplace.projects.status', $project['id']) }}">
                @csrf
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="mp-btn-primary">✅ Mark as Completed</button>
              </form>
            @else
              <div class="mp-info">🎉 You've completed this project!</div>
            @endif
            <button type="button" class="mp-btn-ghost" onclick="closeModal('modal-{{ $project['id'] }}')">Close</button>
          </div>

        </div>
      </div>

    @empty
      <div class="mp-empty">
        <div class="mp-empty-icon">🔍</div>
        <div class="mp-empty-title">No projects match this filter</div>
        <div class="mp-empty-sub">Try removing a filter or updating your profile interests.</div>
        <a href="{{ route('marketplace.feed') }}" style="display:inline-block;margin-top:16px;padding:10px 22px;background:#6366f1;color:#fff;border-radius:10px;font-weight:700;font-size:14px;text-decoration:none;">Reset Filters</a>
      </div>
    @endforelse

  </div>

</div>

<script>
function openModal(id) {
  const overlay = document.getElementById('overlay-' + id.replace('modal-',''));
  const sheet   = document.getElementById(id);
  // Actually let's just use the id directly
  // Find overlay by pattern
  const parts = id.split('-').slice(1).join('-');
  document.getElementById('overlay-' + parts).classList.add('open');
  document.getElementById(id).classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeModal(id) {
  const parts = id.split('-').slice(1).join('-');
  document.getElementById('overlay-' + parts).classList.remove('open');
  document.getElementById(id).classList.remove('open');
  document.body.style.overflow = '';
}
</script>
</body>
</html>
