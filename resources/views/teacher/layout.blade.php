<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduShare - Teacher Panel</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
    <script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(function(OneSignal) {
        OneSignal.init({
            appId: "{{ config('services.onesignal.app_id') }}",
        });

        // Set Tags for Targeting (v16 Syntax)
        @php
            $tProf = \App\Models\Teacher::where('user_id', auth()->id())->first();
        @endphp
        @if($tProf)
            OneSignal.User.addTags({
                role: 'teacher',
                branch: '{{ $tProf->branch }}'
            });
        @endif
    });
    </script>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "background": "#100b1f", "surface": "#100b1f", "surface-container-lowest": "#000000",
              "surface-container-low": "#151026", "surface-container": "#1c162e",
              "surface-container-high": "#221b36", "surface-container-highest": "#28213e",
              "surface-bright": "#2f2747", "on-background": "#ebe1fe", "on-surface": "#ebe1fe",
              "on-surface-variant": "#afa7c2", "primary": "#b3a1ff", "primary-dim": "#7a53ff",
              "on-primary-fixed": "#000000", "secondary": "#d3c5f5", "tertiary": "#699cff",
              "error": "#ff6e84", "outline-variant": "#4b455c"
            },
            fontFamily: { headline: ["Manrope"], body: ["Inter"] }
          }
        }
      }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24; }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }
        
        /* Drawer transition */
        .drawer { transform: translateX(100%); transition: transform 0.32s cubic-bezier(.4,0,.2,1); }
        .drawer.open { transform: translateX(0); }
        .drawer-overlay { display:none; }
        .drawer-overlay.open { display:block; }
        .drawer-body { overflow-y:auto; flex:1; }
        select { appearance: none; -webkit-appearance: none; }

        /* Sidebar transition for mobile */
        .sidebar { transition: transform 0.3s ease-in-out; }
        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); }
            .drawer-panel { width: 100vw !important; }
        }
    </style>
</head>
<body class="bg-background text-on-surface min-h-screen flex">

    <aside id="mainSidebar" class="sidebar fixed inset-y-0 left-0 z-50 w-64 bg-surface-container-low border-r border-outline-variant/20 flex flex-col md:translate-x-0">
        <div class="h-[72px] flex items-center justify-between px-6 border-b border-outline-variant/20">
            <span class="text-xl font-black bg-gradient-to-r from-[#7a53ff] to-[#b3a1ff] bg-clip-text text-transparent select-none">
                📚 EduShare
            </span>
            <button onclick="toggleSidebar()" class="md:hidden text-on-surface-variant hover:text-on-surface">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-4 space-y-2">
            <a href="{{ route('teacher.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('teacher.dashboard') ? 'bg-primary/10 text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-bright/50 hover:text-on-surface' }} transition-colors">
                <span class="material-symbols-outlined">cloud_upload</span> Study Materials
            </a>
            
           <a href="{{ route('attendance.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('attendance.*') ? 'bg-primary/10 text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-bright/50 hover:text-on-surface' }} transition-colors">
                <span class="material-symbols-outlined">fact_check</span> Smart Attendance
            </a>

            <a href="#" class="flex items-center gap-3 px-4 py-3 rounded-xl text-on-surface-variant hover:bg-surface-bright/50 hover:text-on-surface transition-colors">
                <span class="material-symbols-outlined">calendar_month</span> Timetable Viewer
            </a>

            <a href="{{ route('teacher.notices.board') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('teacher.notices.board') ? 'bg-primary/10 text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-bright/50 hover:text-on-surface' }} transition-colors">
                <span class="material-symbols-outlined">description</span> College Notices
            </a>

            <a href="{{ route('teacher.notices.create') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl {{ request()->routeIs('teacher.notices.create') ? 'bg-primary/10 text-primary font-semibold' : 'text-on-surface-variant hover:bg-surface-bright/50 hover:text-on-surface' }} transition-colors">
                <span class="material-symbols-outlined">campaign</span> Publish Notice
            </a>
        </nav>
    </aside>

    <div id="sidebarOverlay" onclick="toggleSidebar()" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden"></div>

    <div class="flex-1 flex flex-col md:ml-64 min-w-0 transition-all relative">
        
        <header id="mainNav" class="bg-[#151026]/85 backdrop-blur-xl border-b border-[#6c3bff]/30 shadow-[0_4px_30px_rgba(0,0,0,0.4)] sticky top-0 z-30 flex justify-between items-center h-[72px] px-4 md:px-10">
            <div class="flex items-center gap-4">
                <button onclick="toggleSidebar()" class="md:hidden text-on-surface p-2 rounded-lg hover:bg-surface-bright">
                    <span class="material-symbols-outlined">menu</span>
                </button>
                <h1 class="text-lg font-bold hidden sm:block">@yield('page_title', 'Dashboard')</h1>
            </div>

            <div class="flex items-center gap-3 md:gap-4">
                @include('partials.nav-notifications')

                <span class="text-on-surface-variant text-sm font-medium hidden sm:inline">
                    Welcome, {{ Auth::user()->username }}!
                </span>

                @php
                    $profile = $teacherProfile ?? null; // Safe fallback for other pages
                    $slug    = preg_replace('/[^a-zA-Z0-9_]/', '', Auth::user()->username);
                    $initial = strtoupper(substr(optional($profile)->display_name ?? Auth::user()->username, 0, 1));
                    $profileComplete = optional($profile)->branch && optional($profile)->semester;
                @endphp

                <div id="navAvatar" onclick="openDrawer()" title="Edit Profile" class="relative w-10 h-10 rounded-full bg-surface-bright border border-primary/20 flex items-center justify-center cursor-pointer hover:shadow-[0_0_0_3px_rgba(179,161,255,0.25)] transition-all select-none">
                    <img src="/uploads/{{ $slug }}/profile.jpg" alt="Profile" class="w-full h-full rounded-full object-cover" onerror="this.style.display='none';document.getElementById('navInitial').style.display='flex'">
                    <span id="navInitial" style="display:none" class="absolute inset-0 flex items-center justify-center font-bold text-primary text-base">{{ $initial }}</span>
                    @if($profileComplete)
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-surface rounded-full"></div>
                    @else
                        <div class="absolute bottom-0 right-0 w-3 h-3 bg-amber-500 border-2 border-surface rounded-full"></div>
                    @endif
                </div>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="font-semibold text-sm text-on-surface-variant hover:text-on-surface px-3 py-2 hover:bg-surface-bright rounded-lg transition-all">Logout</button>
                </form>
            </div>
        </header>

        <main class="flex-1 overflow-x-hidden p-4 sm:p-6 lg:p-10">
            @yield('content')
        </main>
    </div>

    <div id="drawerOverlay" onclick="closeDrawer()" class="drawer-overlay fixed inset-0 bg-black/50 backdrop-blur-sm z-50"></div>

    <aside id="profileDrawer" class="drawer drawer-panel fixed right-0 top-0 h-full w-[400px] max-w-full bg-surface-container-highest/90 backdrop-blur-[24px] z-[60] shadow-[0_20px_40px_rgba(0,0,0,0.5)] border-l border-outline-variant/20 flex flex-col">
        <div class="flex justify-between items-center px-6 py-5 border-b border-outline-variant/15 flex-shrink-0">
            <h2 class="text-lg font-bold text-on-surface">👤 My Profile</h2>
            <button onclick="closeDrawer()" class="p-2 hover:bg-surface-bright rounded-full text-on-surface-variant hover:text-on-surface transition-all">
                <span class="material-symbols-outlined">close</span>
            </button>
        </div>

        <div class="drawer-body px-6 py-6 space-y-6">
            <div class="flex flex-col items-center text-center gap-2">
                <div class="w-24 h-24 rounded-full border-2 border-primary/30 bg-surface-bright flex items-center justify-center overflow-hidden text-4xl font-bold text-primary mb-1">
                    <img src="/uploads/{{ $slug }}/profile.jpg" alt="Profile" class="w-full h-full object-cover" onerror="this.style.display='none';document.getElementById('drawerInitial').style.display='flex'">
                    <span id="drawerInitial" style="display:none" class="w-full h-full flex items-center justify-center">{{ $initial }}</span>
                </div>
                <div class="text-lg font-bold text-on-surface">{{ optional($profile)->display_name ?? Auth::user()->username }}</div>
                <div class="text-sm text-on-surface-variant">{{ Auth::user()->username }}</div>
                @if($profileComplete)
                    <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 bg-emerald-500/15 text-emerald-400 rounded-full">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">check_circle</span> Profile Complete
                    </span>
                @else
                    <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1 bg-amber-500/15 text-amber-400 rounded-full">
                        <span class="material-symbols-outlined text-sm">warning</span> Complete your profile
                    </span>
                @endif
                
                @if(optional($profile)->branch || optional($profile)->semester)
                    <div class="flex flex-wrap gap-2 justify-center mt-1">
                        @if(optional($profile)->branch) <span class="text-xs font-semibold bg-surface-bright text-on-surface-variant px-3 py-1 rounded-full">📌 {{ $profile->branch }}</span> @endif
                        @if(optional($profile)->semester)
                                @php
                                    $sems = json_decode($profile->semester, true);
                                    $semText = is_array($sems) ? implode(', ', $sems) : $profile->semester;
                                @endphp
                                <span class="text-xs font-semibold bg-surface-bright text-on-surface-variant px-3 py-1 rounded-full">
                                    📅 Sem {{ $semText }}
                                </span>
                            @endif
                    </div>
                @endif
            </div>

            <div class="border-t border-outline-variant/15 pt-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">Display Name</p>
                <form method="POST" action="{{ route('teacher.updateName') }}" class="space-y-2">
                    @csrf
                    <input type="text" name="name" value="{{ optional($profile)->display_name ?? '' }}" placeholder="E.g. Mr. Sharma" class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl px-4 py-3 text-on-surface text-sm focus:ring-2 focus:ring-primary/40 focus:outline-none">
                    <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary py-3 rounded-xl font-bold text-sm text-on-primary-fixed hover:scale-[1.02] active:scale-[0.98] transition-all">Update Name</button>
                </form>
            </div>

            <div class="border-t border-outline-variant/15 pt-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">Profile Photo</p>
                <form method="POST" action="{{ route('teacher.updateImage') }}" enctype="multipart/form-data" class="space-y-2">
                    @csrf
                    <div class="border border-dashed border-outline-variant/25 rounded-xl p-4 flex items-center gap-3 bg-surface-container-lowest/50 cursor-pointer">
                        <span class="material-symbols-outlined text-on-surface-variant">image</span>
                        <div class="flex-1 min-w-0">
                            <input type="file" name="profileImage" accept="image/*" 
                            onchange="checkProfileImageSize(this)"
                            class="w-full text-sm text-on-surface-variant file:mr-2 file:py-1 file:px-3 file:rounded-full file:border-0 file:bg-primary/20 file:text-primary cursor-pointer">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary py-3 rounded-xl font-bold text-sm text-on-primary-fixed hover:scale-[1.02] transition-all">Update Photo</button>
                </form>
            </div>

            <div class="border-t border-outline-variant/15 pt-5">
                <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">Branch & Semester</p>
                <form method="POST" action="{{ route('teacher.updateProfile') }}" class="space-y-3">
                    @csrf
                    <!-- Branch display only (not editable) -->
                    @if(optional($profile)->branch)
                    <div>
                        <label class="block text-xs text-on-surface-variant mb-1">🎓 Branch / Department</label>
                        <div class="w-full bg-surface-container-lowest border border-outline-variant/20 rounded-xl px-4 py-3 text-sm text-on-surface-variant cursor-not-allowed">
                            {{ $profile->branch }}
                        </div>
                    </div>
                    @endif
                    <div>
                        <label class="block text-xs text-on-surface-variant mb-2">📅 Active Semesters (Select Multiple)</label>
                        <div class="grid grid-cols-3 gap-2">
                            @php 
                                $selectedSems = json_decode(optional($profile)->semester ?? '[]', true) ?? []; 
                                if(!is_array($selectedSems)) $selectedSems = [$profile->semester]; // Old data support
                            @endphp
                            
                            @for ($i = 1; $i <= 6; $i++)
                                <label class="flex items-center gap-2 bg-surface-container-lowest border border-outline-variant/20 rounded-lg px-3 py-2 cursor-pointer hover:border-primary/50 transition-colors">
                                    <input type="checkbox" name="semesters[]" value="{{ $i }}"
                                        class="rounded bg-surface-container-high border-outline-variant text-primary focus:ring-primary/50 focus:ring-offset-0"
                                        {{ in_array($i, $selectedSems) ? 'checked' : '' }}>
                                    <span class="text-sm text-on-surface-variant">Sem {{ $i }}</span>
                                </label>
                            @endfor
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-primary-dim to-primary py-3 rounded-xl font-bold text-sm text-on-primary-fixed hover:scale-[1.02] transition-all">Save Changes</button>
                </form>
            </div>
        </div>
    </aside>

    <script>
        function toggleSidebar() {
            document.getElementById('mainSidebar').classList.toggle('open');
            document.getElementById('sidebarOverlay').classList.toggle('hidden');
        }
        function openDrawer() {
            document.getElementById('profileDrawer').classList.add('open');
            document.getElementById('drawerOverlay').classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeDrawer() {
            document.getElementById('profileDrawer').classList.remove('open');
            document.getElementById('drawerOverlay').classList.remove('open');
            document.body.style.overflow = '';
        }
        document.addEventListener('keydown', e => { if (e.key === 'Escape') closeDrawer(); });

        @if($errors->has('name') || $errors->has('branch') || $errors->has('semesters') || $errors->has('profileImage'))
            document.addEventListener('DOMContentLoaded', openDrawer);
        @endif

        function checkProfileImageSize(input) {
                    if (input.files && input.files[0]) {
                        const fileSizeMB = input.files[0].size / (1024 * 1024); // Size in MB
                        const maxSizeMB = 5; // Limit set to 5MB

                        if (fileSizeMB > maxSizeMB) {
                            alert(`File too large! Your image is ${fileSizeMB.toFixed(2)} MB.\nPlease select an image smaller than ${maxSizeMB} MB.`);
                    input.value = ''; // Input clear kar do taaki upload na ho
                }
            }
        }
    </script>
    
    @stack('scripts')
</body>
</html>