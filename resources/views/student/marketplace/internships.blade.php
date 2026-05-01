<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Internships – Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @include('student.marketplace._styles')
</head>
<body style="background:#f8faff;min-height:100vh;">

<div class="mp-wrap" x-data="internshipsPage()" xmlns:x-data="http://www.w3.org/1999/xhtml">

  @include('student.marketplace._nav')

  <div class="mp-page-header">
    <div class="mp-page-title">Internships 💼</div>
    <div class="mp-page-sub">{{ count($internships) }} real opportunities for CS & Electronics students</div>
  </div>

  {{-- Filter pills --}}
  <div style="padding:12px 20px 0;background:#fff;border-bottom:1px solid #e2e8f0;">
    <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:12px;scrollbar-width:none;">
      <button @click="filter='All'"    :class="filter==='All'    ? 'mp-pill active' : 'mp-pill'">🔍 All</button>
      <button @click="filter='cert'"   :class="filter==='cert'   ? 'mp-pill active' : 'mp-pill'">📜 Certificate</button>
      <button @click="filter='stipend'" :class="filter==='stipend' ? 'mp-pill active' : 'mp-pill'">💰 Stipend</button>
      <button @click="filter='volunteer'" :class="filter==='volunteer' ? 'mp-pill active' : 'mp-pill'">🤝 Volunteer</button>
      <button @click="filter='remote'" :class="filter==='remote' ? 'mp-pill active' : 'mp-pill'">🌐 Remote</button>
    </div>
  </div>

  <div style="padding:16px 20px;display:flex;flex-direction:column;gap:12px;">

    @foreach($internships as $intern)
      @php
        $certOnly = $intern['stipendType'] === 'Certificate Only';
        $isVol    = stripos($intern['orgType'] ?? '', 'NGO') !== false || $intern['stipendType'] === 'Certificate Only';
      @endphp

      <div class="mp-card"
           x-show="filter === 'All'
             || (filter === 'cert' && {{ $certOnly ? 'true' : 'false' }})
             || (filter === 'stipend' && {{ $intern['stipendType'] === 'Small Stipend' ? 'true' : 'false' }})
             || (filter === 'volunteer' && {{ $isVol ? 'true' : 'false' }})
             || (filter === 'remote' && {{ $intern['remote'] ? 'true' : 'false' }})"
           onclick="openInternModal('imodal-{{ $intern['id'] }}')">

        {{-- Header --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:10px;margin-bottom:8px;">
          <div style="flex:1;">
            <div class="mp-card-title">{{ $intern['title'] }}</div>
            <div style="font-size:12px;color:#94a3b8;margin-top:2px;">{{ $intern['orgName'] }}</div>
          </div>
          <div style="text-align:right;flex-shrink:0;">
            @if($intern['stipendType'] === 'Small Stipend')
              <span class="mp-badge mp-badge-stipend">₹{{ number_format($intern['stipendAmount']) }}/mo</span>
            @else
              <span class="mp-badge mp-badge-cert">📜 Cert</span>
            @endif
          </div>
        </div>

        {{-- Meta --}}
        <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:10px;">
          <span class="mp-badge mp-badge-skill">⏱ {{ $intern['duration'] }}</span>
          <span class="mp-badge {{ $intern['remote'] ? 'mp-badge-green' : 'mp-badge-skill' }}">
            {{ $intern['remote'] ? '🌐 Remote' : '🏢 On-site' }}
          </span>
          @foreach($intern['tags'] as $tag)
            @if(in_array($tag, ['Free to apply','Certificate provided','Stipend provided','Government certified']))
              <span class="mp-badge mp-badge-primary">{{ $tag }}</span>
            @endif
          @endforeach
        </div>

        {{-- Skills --}}
        <div style="display:flex;flex-wrap:wrap;gap:5px;">
          @foreach($intern['skillsNeeded'] as $skill)
            <span class="mp-badge mp-badge-skill">{{ $skill }}</span>
          @endforeach
        </div>

      </div>

      {{-- Modal --}}
      <div class="mp-overlay" id="ioverlay-{{ $intern['id'] }}" onclick="closeInternModal('imodal-{{ $intern['id'] }}')"></div>
      <div class="mp-sheet"  id="imodal-{{ $intern['id'] }}">
        <div class="mp-sheet-handle"><span></span></div>
        <div style="padding:16px 20px 40px;">

          <div style="font-size:20px;font-weight:800;color:#1e293b;margin-bottom:4px;">{{ $intern['title'] }}</div>
          <div style="font-size:13px;color:#94a3b8;margin-bottom:14px;">{{ $intern['orgName'] }} · {{ $intern['orgType'] }}</div>

          <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:16px;">
            @if($intern['stipendType'] === 'Small Stipend')
              <span class="mp-badge mp-badge-stipend">₹{{ number_format($intern['stipendAmount']) }}/month</span>
            @else
              <span class="mp-badge mp-badge-cert">📜 Certificate Only</span>
            @endif
            <span class="mp-badge mp-badge-skill">⏱ {{ $intern['duration'] }}</span>
            <span class="mp-badge {{ $intern['remote'] ? 'mp-badge-green' : 'mp-badge-skill' }}">{{ $intern['remote'] ? '🌐 Remote' : '🏢 On-site' }}</span>
          </div>

          <div class="mp-modal-section-head">🛠 Skills needed</div>
          <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:4px;">
            @foreach($intern['skillsNeeded'] as $skill)
              <span class="mp-badge mp-badge-primary">{{ $skill }}</span>
            @endforeach
          </div>

          <div class="mp-modal-section-head">📋 How to apply</div>
          <p style="font-size:13px;color:#374151;line-height:1.75;margin-bottom:16px;">{{ $intern['howToApply'] }}</p>

          <div style="display:flex;flex-wrap:wrap;gap:6px;margin-bottom:20px;">
            @foreach($intern['tags'] as $tag)
              <span class="mp-badge mp-badge-cert">{{ $tag }}</span>
            @endforeach
          </div>

          <button type="button" class="mp-btn-ghost" onclick="closeInternModal('imodal-{{ $intern['id'] }}')">Close</button>
        </div>
      </div>

    @endforeach

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function internshipsPage() {
  return { filter: 'All' };
}
function openInternModal(id) {
  const parts = id.split('-').slice(1).join('-');
  document.getElementById('ioverlay-' + parts).classList.add('open');
  document.getElementById(id).classList.add('open');
  document.body.style.overflow = 'hidden';
}
function closeInternModal(id) {
  const parts = id.split('-').slice(1).join('-');
  document.getElementById('ioverlay-' + parts).classList.remove('open');
  document.getElementById(id).classList.remove('open');
  document.body.style.overflow = '';
}
</script>
</body>
</html>
