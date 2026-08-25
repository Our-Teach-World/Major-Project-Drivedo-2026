<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Profile – Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @include('student.marketplace._styles')
</head>
<body style="background:#f8faff;min-height:100vh;">

<div class="mp-wrap" x-data="profilePage()" xmlns:x-data="http://www.w3.org/1999/xhtml">

  @include('student.marketplace._nav')

  <div class="mp-page-header">
    <div class="mp-page-title">My Profile 👤</div>
    <div class="mp-page-sub">Update your skills and interests to get better recommendations</div>
  </div>

  @if(session('success'))
    <div class="mp-info" style="margin:12px 20px;background:rgba(34,197,94,.08);border-color:rgba(34,197,94,.3);color:#16a34a;">
      ✅ {{ session('success') }}
    </div>
  @endif

  <div style="padding:20px;">

    {{-- ── DB-sourced Student Info (read-only) ─────────────────────────── --}}
    <div style="background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);border-radius:16px;padding:18px 20px;margin-bottom:22px;color:#fff;box-shadow:0 4px 20px rgba(99,102,241,.25);">
      <div style="font-size:11px;font-weight:700;letter-spacing:.08em;opacity:.75;text-transform:uppercase;margin-bottom:6px;">Your Account</div>
      <div style="font-size:20px;font-weight:800;margin-bottom:12px;">{{ $studentInfo['display_name'] }}</div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:10px 12px;">
          <div style="font-size:10px;opacity:.75;margin-bottom:2px;">BRANCH</div>
          <div style="font-size:13px;font-weight:700;">{{ $studentInfo['branch'] }}</div>
        </div>
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:10px 12px;">
          <div style="font-size:10px;opacity:.75;margin-bottom:2px;">SEMESTER</div>
          <div style="font-size:13px;font-weight:700;">{{ $studentInfo['semester_label'] }}</div>
        </div>
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:10px 12px;">
          <div style="font-size:10px;opacity:.75;margin-bottom:2px;">YEAR</div>
          <div style="font-size:13px;font-weight:700;">Year {{ $studentInfo['year'] }}</div>
        </div>
        <div style="background:rgba(255,255,255,.15);border-radius:10px;padding:10px 12px;">
          <div style="font-size:10px;opacity:.75;margin-bottom:2px;">ENROLLMENT</div>
          <div style="font-size:13px;font-weight:700;">{{ $studentInfo['enrollment_no'] }}</div>
        </div>
      </div>
      <div style="margin-top:10px;font-size:11px;opacity:.65;">✅ Year auto-detected from semester {{ $studentInfo['semester'] }} • Projects shown accordingly</div>
    </div>

    {{-- ── Editable Skills & Interests ───────────────────────────────────── --}}
    <form method="POST" action="{{ route('marketplace.profile.update') }}">
      @csrf

      {{-- Skills --}}
      <div style="margin-bottom:20px;">
        <label style="display:block;font-size:13px;font-weight:700;color:#1e293b;margin-bottom:10px;">🛠 Your Skills</label>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
          @php
            $allSkills = ['HTML/CSS','JavaScript','Python','C','C++','Java','React','Node.js','SQL','Arduino','Git'];
          @endphp
          @foreach($allSkills as $skill)
            <button type="button"
                    class="mp-chip"
                    :class="skills.includes('{{ $skill }}') ? 'selected' : ''"
                    @click="toggleArr(skills, '{{ $skill }}')">
              {{ $skill }}
            </button>
          @endforeach
        </div>
        <template x-for="s in skills" :key="s">
          <input type="hidden" name="skills[]" :value="s">
        </template>
        <div class="mp-info" style="margin-top:10px;" x-show="skills.length > 0">
          ✅ <strong x-text="skills.length"></strong> skill(s) selected
        </div>
      </div>

      {{-- Interests --}}
      <div style="margin-bottom:28px;">
        <label style="display:block;font-size:13px;font-weight:700;color:#1e293b;margin-bottom:10px;">💡 Your Interests</label>
        <div style="display:flex;flex-wrap:wrap;gap:8px;">
          @php
            $allInterests = ['Web Development','Machine Learning','IoT','Mobile Apps','Game Development','Cybersecurity','Data Analytics','Automation','UI/UX Design'];
          @endphp
          @foreach($allInterests as $interest)
            <button type="button"
                    class="mp-chip"
                    :class="interests.includes('{{ $interest }}') ? 'selected' : ''"
                    @click="toggleArr(interests, '{{ $interest }}')">
              {{ $interest }}
            </button>
          @endforeach
        </div>
        <template x-for="i in interests" :key="i">
          <input type="hidden" name="interests[]" :value="i">
        </template>
        <div class="mp-info" style="margin-top:10px;" x-show="interests.length > 0">
          ✅ <strong x-text="interests.length"></strong> interest(s) selected
        </div>
      </div>

      <button type="submit" class="mp-btn-primary" :disabled="!canSave()">Save Profile & Refresh Feed</button>
    </form>

    {{-- Reset --}}
    <div style="margin-top:20px;border-top:1px solid #e2e8f0;padding-top:20px;padding-bottom:40px;">
      <p style="font-size:13px;color:#94a3b8;margin-bottom:12px;">Reset skills/interests and redo the onboarding:</p>
      <form method="POST" action="{{ route('marketplace.reset') }}">
        @csrf
        <button type="submit" class="mp-btn-ghost" style="color:#ef4444;border-color:rgba(239,68,68,.3);" onclick="return confirm('Reset your skills & interests and start over?')">
          🔁 Reset & Start Over
        </button>
      </form>
    </div>

  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function profilePage() {
  return {
    skills: {!! json_encode($profile['skills'] ?? []) !!},
    interests: {!! json_encode($profile['interests'] ?? []) !!},

    toggleArr(arr, val) {
      const idx = arr.indexOf(val);
      if (idx >= 0) arr.splice(idx, 1);
      else arr.push(val);
    },

    canSave() {
      return this.skills.length > 0 && this.interests.length > 0;
    }
  };
}
</script>
</body>
</html>
