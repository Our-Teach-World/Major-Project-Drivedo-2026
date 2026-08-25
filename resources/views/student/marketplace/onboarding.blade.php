<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Welcome – Project Marketplace</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  @include('student.marketplace._styles')
</head>
<body style="background:#f8faff;min-height:100vh;">

<div class="mp-wrap" x-data="onboarding()" x-init="init()" xmlns:x-data="http://www.w3.org/1999/xhtml">

  {{-- Back to Dashboard --}}
  <div style="padding:12px 20px 0;">
    <a href="{{ route('student.dashboard') }}" style="font-size:13px;color:#64748b;text-decoration:none;display:inline-flex;align-items:center;gap:4px;">
      ← Dashboard
    </a>
  </div>

  {{-- Header with brand --}}
  <div style="padding:20px 20px 12px;text-align:center;">
    <div style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:999px;background:rgba(99,102,241,.1);border:1.5px solid rgba(99,102,241,.25);margin-bottom:16px;">
      <span style="font-size:12px;color:#6366f1;font-weight:700;">🚀 CS/IT Project Marketplace</span>
    </div>

    {{-- Personalized welcome card --}}
    <div style="background:linear-gradient(135deg,#6366f1 0%,#8b5cf6 100%);border-radius:16px;padding:18px 20px;margin-bottom:20px;text-align:left;color:#fff;box-shadow:0 4px 20px rgba(99,102,241,.3);">
      <div style="font-size:13px;opacity:.85;margin-bottom:4px;">Welcome back 👋</div>
      <div style="font-size:20px;font-weight:800;margin-bottom:10px;">{{ $studentInfo['display_name'] }}</div>
      <div style="display:flex;flex-wrap:wrap;gap:8px;">
        <span style="background:rgba(255,255,255,.18);border-radius:999px;padding:3px 12px;font-size:12px;font-weight:600;">
          🎓 {{ $studentInfo['branch'] }}
        </span>
        <span style="background:rgba(255,255,255,.18);border-radius:999px;padding:3px 12px;font-size:12px;font-weight:600;">
          📋 {{ $studentInfo['semester_label'] }}
        </span>
        <span style="background:rgba(255,255,255,.18);border-radius:999px;padding:3px 12px;font-size:12px;font-weight:600;">
          📅 Year {{ $studentInfo['year'] }}
        </span>
      </div>
    </div>

    {{-- Year auto-detected notice --}}
    <div style="display:flex;align-items:center;gap:8px;background:rgba(34,197,94,.07);border:1px solid rgba(34,197,94,.25);border-radius:10px;padding:10px 14px;margin-bottom:20px;text-align:left;">
      <span style="font-size:18px;">✅</span>
      <div>
        <div style="font-size:13px;font-weight:700;color:#15803d;">Year auto-detected from your records</div>
        <div style="font-size:12px;color:#64748b;">We found you're in <strong>Semester {{ $studentInfo['semester'] }}</strong> — mapped to <strong>Year {{ $studentInfo['year'] }}</strong>. No need to select manually.</div>
      </div>
    </div>

    <h1 style="font-size:22px;font-weight:800;color:#1e293b;margin-bottom:6px;" class="mp-gradient-text" x-text="titles[step]"></h1>
    <p style="font-size:14px;color:#64748b;" x-text="subtitles[step]"></p>
  </div>

  {{-- Progress Dots (2 steps now) --}}
  <div style="display:flex;justify-content:center;gap:10px;margin-bottom:24px;">
    <div class="mp-dot" :class="step >= 0 ? 'active' : ''"></div>
    <div class="mp-dot" :class="step >= 1 ? 'active' : ''"></div>
  </div>

  <div style="padding:0 20px;max-width:520px;margin:0 auto;">

    {{-- Step 0: Skills --}}
    <div x-show="step === 0">
      <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:16px;">
        <template x-for="s in allSkills" :key="s">
          <button type="button" class="mp-chip" :class="skills.includes(s) ? 'selected' : ''"
                  @click="toggleArr(skills, s)">
            <span x-text="s"></span>
          </button>
        </template>
      </div>
      <div class="mp-info" x-show="skills.length > 0">
        ✅ <strong x-text="skills.length"></strong> skill(s) selected — <span x-text="skills.join(', ')"></span>
      </div>
    </div>

    {{-- Step 1: Interests --}}
    <div x-show="step === 1">
      <div style="display:flex;flex-wrap:wrap;gap:8px;">
        <template x-for="interest in allInterests" :key="interest">
          <button type="button" class="mp-chip" :class="interests.includes(interest) ? 'selected' : ''"
                  @click="toggleArr(interests, interest)">
            <span x-text="interest"></span>
          </button>
        </template>
      </div>
      <div class="mp-info" style="margin-top:14px;" x-show="interests.length > 0">
        ✅ <strong x-text="interests.length"></strong> interest(s) selected — <span x-text="interests.join(', ')"></span>
      </div>
    </div>

    {{-- Navigation --}}
    <div style="margin-top:28px;padding-bottom:40px;">
      <form id="onboarding-form" method="POST" action="{{ route('marketplace.onboarding.save') }}" style="display:none;">
        @csrf
      </form>

      <button type="button" class="mp-btn-primary" :disabled="!canProceed()" @click="next()">
        <span x-text="step < 1 ? 'Continue →' : 'Show My Projects 🚀'"></span>
      </button>

      <button type="button" x-show="step > 0" class="mp-btn-ghost" style="margin-top:10px;" @click="step--">
        ← Go back
      </button>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
function onboarding() {
  return {
    step: 0,
    skills: [],
    interests: [],
    titles: ['What skills do you have?', 'What interests you most?'],
    subtitles: [
      'Even basic skills count — be honest',
      'Pick as many as you like'
    ],
    allSkills: ['HTML/CSS','JavaScript','Python','C','C++','Java','React','Node.js','SQL','Arduino','Git'],
    allInterests: ['Web Development','Machine Learning','IoT','Mobile Apps','Game Development','Cybersecurity','Data Analytics','Automation','UI/UX Design'],

    init() {},

    toggleArr(arr, val) {
      const idx = arr.indexOf(val);
      if (idx >= 0) arr.splice(idx, 1);
      else arr.push(val);
    },

    canProceed() {
      if (this.step === 0) return this.skills.length > 0;
      if (this.step === 1) return this.interests.length > 0;
      return false;
    },

    next() {
      if (!this.canProceed()) return;
      if (this.step < 1) {
        this.step++;
      } else {
        this.submit();
      }
    },

    submit() {
      const form = document.getElementById('onboarding-form');
      form.querySelectorAll('[name="skills[]"],[name="interests[]"]').forEach(el => el.remove());
      this.skills.forEach(s => {
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'skills[]'; inp.value = s;
        form.appendChild(inp);
      });
      this.interests.forEach(i => {
        const inp = document.createElement('input');
        inp.type = 'hidden'; inp.name = 'interests[]'; inp.value = i;
        form.appendChild(inp);
      });
      form.submit();
    }
  };
}
</script>
</body>
</html>
