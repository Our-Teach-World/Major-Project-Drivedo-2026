<!DOCTYPE html>
<html class="dark" lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EduShare - Teaching Hub</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@400;500;600&family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script id="tailwind-config">
      tailwind.config = {
        darkMode: "class",
        theme: {
          extend: {
            colors: {
              "background":                "#100b1f",
              "surface":                   "#100b1f",
              "surface-dim":               "#100b1f",
              "surface-container-lowest":  "#000000",
              "surface-container-low":     "#151026",
              "surface-container":         "#1c162e",
              "surface-container-high":    "#221b36",
              "surface-container-highest": "#28213e",
              "surface-bright":            "#2f2747",
              "surface-variant":           "#28213e",
              "on-background":             "#ebe1fe",
              "on-surface":                "#ebe1fe",
              "on-surface-variant":        "#afa7c2",
              "primary":                   "#b3a1ff",
              "primary-dim":               "#7a53ff",
              "primary-container":         "#a690ff",
              "primary-fixed":             "#a690ff",
              "on-primary":               "#310093",
              "on-primary-fixed":         "#000000",
              "secondary":                "#d3c5f5",
              "secondary-container":       "#4b4168",
              "tertiary":                  "#699cff",
              "tertiary-dim":              "#699cff",
              "tertiary-container":        "#4388fd",
              "error":                     "#ff6e84",
              "outline":                   "#79728b",
              "outline-variant":           "#4b455c",
              "surface-tint":              "#b3a1ff",
            },
            fontFamily: {
              headline: ["Manrope"],
              body:     ["Inter"],
              label:    ["Inter"],
            },
            borderRadius: {
              DEFAULT: "0.25rem",
              lg:      "0.5rem",
              xl:      "0.75rem",
              "2xl":   "1rem",
              "3xl":   "1.5rem",
              full:    "9999px",
            },
          },
        },
      }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3 { font-family: 'Manrope', sans-serif; }

        /* Drawer transition */
        .drawer { transform: translateX(100%); transition: transform 0.32s cubic-bezier(.4,0,.2,1); }
        .drawer.open { transform: translateX(0); }
        .drawer-overlay { display:none; }
        .drawer-overlay.open { display:block; }

        /* Upload lock */
        .upload-locked { opacity:0.35; pointer-events:none; transition:opacity 0.25s; }

        /* Scrollable drawer body */
        .drawer-body { overflow-y:auto; flex:1; }

        /* Custom select arrow */
        select { appearance: none; -webkit-appearance: none; }

        /* Smooth file row hover */
        .file-row { transition: background 0.18s, transform 0.18s; }
        .file-row:hover { transform: translateX(4px); }

        /* Navbar scroll effect */
        .navbar-scrolled { backdrop-filter: blur(20px) !important; background: rgba(21,16,38,0.92) !important; }

        @media (max-width: 640px) {
            .drawer-panel { width: 100vw !important; }
        }
    </style>
</head>
<body class="bg-background text-on-surface min-h-screen">

{{-- ── NAVBAR ── --}}
<header id="mainNav"
        class="bg-[#151026]/85 backdrop-blur-xl border-b border-[#6c3bff]/30
               shadow-[0_4px_30px_rgba(0,0,0,0.4)] sticky top-0 z-40
               flex justify-between items-center h-[72px] px-6 md:px-10 w-full">

    <div class="flex items-center gap-6">
        <span class="text-xl font-black bg-gradient-to-r from-[#7a53ff] to-[#b3a1ff] bg-clip-text text-transparent select-none">
            📚 EduShare
        </span>
    </div>

    <div class="flex items-center gap-3 md:gap-4">
        {{-- Welcome text (hidden on tiny screens) --}}
        <span class="text-on-surface-variant text-sm font-medium hidden sm:inline">
            Welcome, {{ Auth::user()->username }}!
        </span>

        {{-- Profile Avatar --}}
        @php
            $slug    = preg_replace('/[^a-zA-Z0-9_]/', '', Auth::user()->username);
            $initial = strtoupper(substr(optional($teacherProfile)->display_name ?? Auth::user()->username, 0, 1));
            $profileComplete = optional($teacherProfile)->branch && optional($teacherProfile)->semester;
        @endphp

        <div id="navAvatar" onclick="openDrawer()" title="Edit Profile"
             class="relative w-10 h-10 rounded-full bg-surface-bright border border-primary/20
                    flex items-center justify-center cursor-pointer
                    hover:shadow-[0_0_0_3px_rgba(179,161,255,0.25)] transition-all select-none">
            <img src="/uploads/{{ $slug }}/profile.jpg" alt="Profile"
                 class="w-full h-full rounded-full object-cover"
                 onerror="this.style.display='none';document.getElementById('navInitial').style.display='flex'">
            <span id="navInitial" style="display:none"
                  class="absolute inset-0 flex items-center justify-center font-bold text-primary text-base">
                {{ $initial }}
            </span>
            {{-- Status dot --}}
            @if($profileComplete)
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-500 border-2 border-surface rounded-full"></div>
            @else
                <div class="absolute bottom-0 right-0 w-3 h-3 bg-amber-500 border-2 border-surface rounded-full"></div>
            @endif
        </div>

        {{-- Logout --}}
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit"
                    class="font-semibold text-sm text-on-surface-variant
                           hover:text-on-surface px-3 py-2
                           hover:bg-surface-bright rounded-lg transition-all">
                Logout
            </button>
        </form>
    </div>
</header>


{{-- ── DRAWER OVERLAY ── --}}
<div id="drawerOverlay" onclick="closeDrawer()"
     class="drawer-overlay fixed inset-0 bg-black/50 backdrop-blur-sm z-40"></div>


{{-- ── PROFILE DRAWER ── --}}
<aside id="profileDrawer"
       class="drawer drawer-panel fixed right-0 top-0 h-full w-[400px] max-w-full
              bg-surface-container-highest/90 backdrop-blur-[24px] z-50
              shadow-[0_20px_40px_rgba(0,0,0,0.5),0_0_30px_rgba(108,59,255,0.08)]
              border-l border-outline-variant/20 flex flex-col">

    {{-- Drawer Header --}}
    <div class="flex justify-between items-center px-6 py-5 border-b border-outline-variant/15 flex-shrink-0">
        <h2 class="text-lg font-bold text-on-surface">👤 My Profile</h2>
        <button onclick="closeDrawer()"
                class="p-2 hover:bg-surface-bright rounded-full text-on-surface-variant hover:text-on-surface transition-all">
            <span class="material-symbols-outlined">close</span>
        </button>
    </div>

    {{-- Drawer Body --}}
    <div class="drawer-body px-6 py-6 space-y-6">

        {{-- Avatar + Name Summary --}}
        <div class="flex flex-col items-center text-center gap-2">
            <div class="w-24 h-24 rounded-full border-2 border-primary/30 bg-surface-bright
                        flex items-center justify-center overflow-hidden text-4xl font-bold text-primary mb-1">
                <img src="/uploads/{{ $slug }}/profile.jpg" alt="Profile"
                     class="w-full h-full object-cover"
                     onerror="this.style.display='none';document.getElementById('drawerInitial').style.display='flex'">
                <span id="drawerInitial" style="display:none"
                      class="w-full h-full flex items-center justify-center">{{ $initial }}</span>
            </div>

            <div class="text-lg font-bold text-on-surface">
                {{ optional($teacherProfile)->display_name ?? Auth::user()->username }}
            </div>
            <div class="text-sm text-on-surface-variant">@{{ Auth::user()->username }}</div>

            @if($profileComplete)
                <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1
                             bg-emerald-500/15 text-emerald-400 rounded-full">
                    <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">check_circle</span>
                    Profile Complete
                </span>
            @else
                <span class="inline-flex items-center gap-1 text-xs font-bold px-3 py-1
                             bg-amber-500/15 text-amber-400 rounded-full">
                    <span class="material-symbols-outlined text-sm">warning</span>
                    Complete your profile
                </span>
            @endif

            {{-- Info chips --}}
            @if(optional($teacherProfile)->branch || optional($teacherProfile)->semester)
                <div class="flex flex-wrap gap-2 justify-center mt-1">
                    @if(optional($teacherProfile)->branch)
                        <span class="text-xs font-semibold bg-surface-bright text-on-surface-variant px-3 py-1 rounded-full">
                            📌 {{ $teacherProfile->branch }}
                        </span>
                    @endif
                    @if(optional($teacherProfile)->semester)
                        <span class="text-xs font-semibold bg-surface-bright text-on-surface-variant px-3 py-1 rounded-full">
                            📅 Semester {{ $teacherProfile->semester }}
                        </span>
                    @endif
                </div>
            @endif
        </div>

        {{-- ── Display Name ── --}}
        <div class="border-t border-outline-variant/15 pt-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">Display Name</p>
            <form method="POST" action="{{ route('teacher.updateName') }}" class="space-y-2">
                @csrf
                <input type="text" name="name"
                       value="{{ optional($teacherProfile)->display_name ?? '' }}"
                       placeholder="E.g. Mr. Sharma"
                       class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl
                              px-4 py-3 text-on-surface text-sm placeholder-on-surface-variant/50
                              focus:ring-2 focus:ring-primary/40 focus:border-primary focus:outline-none transition-all">
                <button type="submit"
                        class="w-full bg-gradient-to-r from-primary-dim to-primary py-3 rounded-xl
                               font-bold text-sm text-on-primary-fixed
                               shadow-lg shadow-primary-dim/20
                               hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Update Name
                </button>
            </form>
        </div>

        {{-- ── Profile Photo ── --}}
        <div class="border-t border-outline-variant/15 pt-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">Profile Photo</p>
            <form method="POST" action="{{ route('teacher.updateImage') }}" enctype="multipart/form-data" class="space-y-2">
                @csrf
                <div class="border border-dashed border-outline-variant/25 rounded-xl p-4
                            flex items-center gap-3 bg-surface-container-lowest/50 cursor-pointer
                            hover:bg-surface-bright/20 transition-colors">
                    <span class="material-symbols-outlined text-on-surface-variant">image</span>
                    <div class="flex-1 min-w-0">
                        <input type="file" name="profileImage" accept="image/*"
                               class="w-full text-sm text-on-surface-variant file:mr-2
                                      file:py-1 file:px-3 file:rounded-full file:border-0
                                      file:text-xs file:font-semibold
                                      file:bg-primary/20 file:text-primary
                                      hover:file:bg-primary/30 cursor-pointer">
                    </div>
                </div>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-primary-dim to-primary py-3 rounded-xl
                               font-bold text-sm text-on-primary-fixed
                               shadow-lg shadow-primary-dim/20
                               hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Update Photo
                </button>
            </form>
        </div>

        {{-- ── Branch & Semester ── --}}
        <div class="border-t border-outline-variant/15 pt-5">
            <p class="text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">Branch & Semester</p>
            <form method="POST" action="{{ route('teacher.updateProfile') }}" class="space-y-3">
                @csrf
                <div class="relative">
                    <label class="block text-xs text-on-surface-variant mb-1">🎓 Branch / Department</label>
                    <select name="branch" required
                            class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl
                                   px-4 py-3 text-on-surface text-sm
                                   focus:ring-2 focus:ring-primary/40 focus:border-primary focus:outline-none transition-all">
                        <option value="">-- Select Branch --</option>
                        @foreach ([
                            'Civil Engineering',
                            'Mechanical Engineering',
                            'Electrical Engineering',
                            'Electronics Engineering (EL)',
                            'Computer Engineering/Science & Engineering',
                            'Instrumentation & Control Plastic Technology',
                            'Chemical Engineering',
                        ] as $branch)
                            <option value="{{ $branch }}"
                                {{ optional($teacherProfile)->branch === $branch ? 'selected' : '' }}>
                                {{ $branch }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-on-surface-variant mb-1">📅 Primary Semester</label>
                    <select name="semester" required
                            class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl
                                   px-4 py-3 text-on-surface text-sm
                                   focus:ring-2 focus:ring-primary/40 focus:border-primary focus:outline-none transition-all">
                        <option value="">-- Select Semester --</option>
                        @for ($i = 1; $i <= 6; $i++)
                            <option value="{{ $i }}"
                                {{ optional($teacherProfile)->semester == $i ? 'selected' : '' }}>
                                Semester {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
                <button type="submit"
                        class="w-full bg-gradient-to-r from-primary-dim to-primary py-3 rounded-xl
                               font-bold text-sm text-on-primary-fixed
                               shadow-lg shadow-primary-dim/20
                               hover:scale-[1.02] active:scale-[0.98] transition-all">
                    Save Changes
                </button>
            </form>
        </div>

    </div>{{-- /drawer-body --}}
</aside>


{{-- ── MAIN CONTENT ── --}}
<main class="max-w-[960px] mx-auto px-4 sm:px-6 py-10">

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl
                    flex items-center gap-3 text-emerald-400">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-xl
                    flex items-start gap-3 text-error">
            <span class="material-symbols-outlined text-lg mt-0.5">error</span>
            <div class="text-sm space-y-0.5">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Page Header --}}
    <header class="mb-10">
        <h1 class="text-4xl font-extrabold text-on-surface tracking-tight mb-2">Your Teaching Hub</h1>
        <p class="text-on-surface-variant">Manage, upload and curate your academic materials for students.</p>
    </header>

    {{-- ── Upload Card ── --}}
    <section class="bg-surface-container rounded-3xl border border-outline-variant/10
                    shadow-2xl relative overflow-hidden mb-8">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-dim/5 to-transparent pointer-events-none"></div>
        <div class="p-6 sm:p-8 relative">
            <div class="flex items-center gap-3 mb-8">
                <span class="material-symbols-outlined text-primary text-3xl">cloud_upload</span>
                <h2 class="text-2xl font-bold text-on-surface">Upload Study Materials</h2>
            </div>

            <form method="POST" action="{{ route('teacher.upload') }}"
                  enctype="multipart/form-data" id="uploadForm" class="space-y-6">
                @csrf

                {{-- Step 1: Semester --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">
                        Step 1: Select Semester *
                    </label>
                    <div class="relative">
                        <select name="semester" id="uploadSemester" required
                                onchange="onSemesterChange(this)"
                                class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl
                                       px-4 py-4 text-on-surface text-sm
                                       focus:ring-2 focus:ring-primary/40 focus:border-primary focus:outline-none
                                       transition-all cursor-pointer">
                            <option value="">-- Select Semester --</option>
                            @for ($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}"
                                    {{ optional($teacherProfile)->semester == $i ? 'selected' : '' }}>
                                    Semester {{ $i }}
                                </option>
                            @endfor
                        </select>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">
                            expand_more
                        </span>
                    </div>
                    <p id="uploadHint" class="hidden mt-2 text-emerald-400 text-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">check_circle</span>
                        Semester selected! Now choose your files below.
                    </p>
                </div>

                {{-- Step 2: Files (locked until semester chosen) --}}
                <div id="uploadFileArea" class="upload-locked space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">
                            Step 2: Select Files (Max 10MB each)
                        </label>
                        {{-- Drop zone --}}
                        <label for="fileInput"
                               class="border-2 border-dashed border-outline-variant/20 rounded-2xl p-8
                                      flex flex-col items-center justify-center bg-surface-container-lowest
                                      hover:bg-surface-bright/20 transition-colors group cursor-pointer">
                            <span class="material-symbols-outlined text-4xl text-outline-variant group-hover:text-primary transition-colors mb-3">
                                upload_file
                            </span>
                            <p class="text-on-surface font-medium text-sm mb-1">Click to browse or drag and drop</p>
                            <p class="text-on-surface-variant text-xs">PDF, DOCX, XLSX, PPTX, TXT, PNG, JPG, MP3, MP4, ZIP, etc.</p>
                            <div id="fileCount" class="hidden mt-3 text-primary text-xs font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">attach_file</span>
                                <span id="fileCountText"></span> file(s) selected
                            </div>
                        </label>
                        <input type="file" name="files[]" id="fileInput" multiple disabled
                               class="hidden" onchange="updateFileCount(this)">
                    </div>

                    {{-- Upload Button --}}
                    <button type="submit" id="uploadBtn" disabled
                            class="w-full bg-gradient-to-r from-primary-dim to-primary py-4 rounded-xl
                                   font-bold text-on-primary-fixed
                                   shadow-lg shadow-primary-dim/20
                                   hover:scale-[1.02] active:scale-[0.98] transition-all
                                   flex items-center justify-center gap-2
                                   disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100">
                        <span class="material-symbols-outlined">rocket_launch</span>
                        Upload Files
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- ── Files List ── --}}
    <section>
        <div class="flex items-center justify-between mb-4 px-1">
            <h2 class="text-xl font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-tertiary">folder_shared</span>
                Your Uploaded Files
            </h2>
        </div>
        <div id="filesList" class="space-y-2">
            <div class="flex items-center justify-center p-10 text-on-surface-variant text-sm">
                <span class="material-symbols-outlined animate-spin mr-2">progress_activity</span>
                Loading files…
            </div>
        </div>
    </section>

</main>


<script>
    /* ── Drawer ── */
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

    @if(session('success') || $errors->any())
        document.addEventListener('DOMContentLoaded', openDrawer);
    @endif

    /* ── Navbar scroll effect ── */
    window.addEventListener('scroll', () => {
        const nav = document.getElementById('mainNav');
        nav.classList.toggle('navbar-scrolled', window.scrollY > 10);
    });

    /* ── Semester → unlock file area ── */
    function onSemesterChange(sel) {
        const area    = document.getElementById('uploadFileArea');
        const input   = document.getElementById('fileInput');
        const btn     = document.getElementById('uploadBtn');
        const hint    = document.getElementById('uploadHint');
        const dropLabel = document.querySelector('label[for="fileInput"]');

        if (sel.value) {
            area.classList.remove('upload-locked');
            input.disabled = false;
            btn.disabled   = false;
            hint.classList.remove('hidden');
            hint.classList.add('flex');
        } else {
            area.classList.add('upload-locked');
            input.disabled = true;
            btn.disabled   = true;
            hint.classList.add('hidden');
            hint.classList.remove('flex');
            input.value = '';
            document.getElementById('fileCount').classList.add('hidden');
        }
    }
    // Auto-unlock if semester pre-selected
    document.addEventListener('DOMContentLoaded', () => {
        onSemesterChange(document.getElementById('uploadSemester'));
    });

    /* ── File count ── */
    function updateFileCount(input) {
        const div  = document.getElementById('fileCount');
        const span = document.getElementById('fileCountText');
        if (input.files.length > 0) {
            span.textContent = input.files.length;
            div.classList.remove('hidden');
            div.classList.add('flex');
        } else {
            div.classList.add('hidden');
            div.classList.remove('flex');
        }
    }

    /* ── Load file list ── */
    function loadFiles() {
        fetch('{{ route("teacher.files") }}')
            .then(r => r.json())
            .then(files => {
                const list = document.getElementById('filesList');
                if (!files.length) {
                    list.innerHTML = `
                    <div class="flex flex-col items-center justify-center p-14
                                bg-surface-container/30 border border-dashed border-outline-variant/10 rounded-2xl">
                        <span class="material-symbols-outlined text-5xl text-outline-variant/40 mb-3">folder_off</span>
                        <p class="text-on-surface-variant font-medium">No files uploaded yet</p>
                        <p class="text-outline text-xs mt-1">Upload your first study material above.</p>
                    </div>`;
                    return;
                }
                list.innerHTML = files.map(file => {
                    const icon     = getFileIcon(file.filename);
                    const date     = new Date(file.uploaded_at).toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'});
                    const semBadge = file.semester
                        ? `<span class="bg-tertiary-container/20 text-tertiary-dim text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Sem ${file.semester}</span>`
                        : '';
                    return `
                    <div class="file-row flex items-center justify-between p-4
                                bg-surface-container-high rounded-xl group
                                hover:bg-surface-bright" id="file-row-${file.id}">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-surface-container-highest
                                        flex items-center justify-center text-xl flex-shrink-0">${icon}</div>
                            <div class="min-w-0">
                                <p class="font-semibold text-on-surface text-sm truncate">${file.filename}</p>
                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                    ${semBadge}
                                    <span class="text-on-surface-variant text-xs">${date}</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0 ml-3">
                            <a href="/teacher/file/${file.id}/preview" target="_blank"
                               class="flex items-center gap-1 px-3 py-2 text-xs font-bold
                                      text-tertiary hover:bg-tertiary/10 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-sm">visibility</span>
                                <span class="hidden sm:inline">Preview</span>
                            </a>
                            <button onclick="deleteFile(${file.id}, this)"
                                    class="flex items-center gap-1 px-3 py-2 text-xs font-bold
                                           text-error hover:bg-error/10 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-sm">delete</span>
                                <span class="hidden sm:inline">Delete</span>
                            </button>
                        </div>
                    </div>`;
                }).join('');
            })
            .catch(() => {
                document.getElementById('filesList').innerHTML =
                    `<p class="text-center text-error text-sm py-6">⚠ Failed to load files. Please refresh.</p>`;
            });
    }

    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const map = {
            pdf:'📕', docx:'📝', doc:'📝', xlsx:'📊', xls:'📊', pptx:'📋', ppt:'📋', txt:'📄',
            png:'🖼️', jpg:'🖼️', jpeg:'🖼️', gif:'🖼️', svg:'🖼️',
            mp3:'🎵', m4a:'🎵', wav:'🎵',
            mp4:'🎬', mkv:'🎬', avi:'🎬',
            zip:'📦', rar:'📦',
        };
        return map[ext] || '📎';
    }

    function deleteFile(id, btn) {
        if (!confirm('Permanently delete this file?')) return;
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">progress_activity</span>';

        fetch(`/teacher/file/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById('file-row-' + id);
                row.style.opacity = '0';
                row.style.transition = 'opacity 0.35s';
                setTimeout(() => {
                    row.remove();
                    if (!document.querySelector('.file-row')) loadFiles();
                }, 380);
            } else {
                btn.disabled = false;
                btn.innerHTML = '<span class="material-symbols-outlined text-sm">delete</span><span class="hidden sm:inline">Delete</span>';
                alert('Delete failed.');
            }
        })
        .catch(() => {
            btn.disabled = false;
            btn.innerHTML = '<span class="material-symbols-outlined text-sm">delete</span><span class="hidden sm:inline">Delete</span>';
            alert('Network error.');
        });
    }

    loadFiles();
</script>

</body>
</html>
