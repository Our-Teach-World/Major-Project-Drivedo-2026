{{-- Shared Marketplace Nav Bar --}}
@php
  $inProgress = 0;
  foreach(session('marketplace_projects', []) as $status) {
    if ($status === 'started') $inProgress++;
  }
  $currentRoute = request()->route()->getName();
@endphp
<nav class="mp-nav">
  <a href="{{ route('marketplace.feed') }}" class="{{ $currentRoute === 'marketplace.feed' ? 'active' : '' }}">
    🚀 Projects
  </a>
  <a href="{{ route('marketplace.internships') }}" class="{{ $currentRoute === 'marketplace.internships' ? 'active' : '' }}">
    💼 Internships
  </a>
  <a href="{{ route('marketplace.my-projects') }}" class="{{ $currentRoute === 'marketplace.my-projects' ? 'active' : '' }}">
    📂 My Projects
    @if($inProgress > 0)
      <span class="mp-nav-badge">{{ $inProgress }}</span>
    @endif
  </a>
  <a href="{{ route('marketplace.profile') }}" class="{{ $currentRoute === 'marketplace.profile' ? 'active' : '' }}">
    👤 Profile
  </a>
</nav>
