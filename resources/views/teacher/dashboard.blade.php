@extends('teacher.layout')

@section('page_title', 'Study Materials')

@section('content')

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3 text-emerald-400">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm font-medium">{{ session('success') }}</p>
        </div>
    @endif
    @if($errors->any())
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-xl flex items-start gap-3 text-error">
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


    {{-- Notice preview has been moved to a dedicated page accessible via the sidebar --}}

    {{-- ── Upload Card ── --}}
    <section class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-2xl relative overflow-hidden mb-8">
        <div class="absolute inset-0 bg-gradient-to-br from-primary-dim/5 to-transparent pointer-events-none"></div>
        <div class="p-6 sm:p-8 relative">
            <div class="flex items-center gap-3 mb-8">
                <span class="material-symbols-outlined text-primary text-3xl">cloud_upload</span>
                <h2 class="text-2xl font-bold text-on-surface">Upload Study Materials</h2>
            </div>

            <form method="POST" action="{{ route('teacher.upload') }}" enctype="multipart/form-data" id="uploadForm" class="space-y-6">
                @csrf

                {{-- Step 1: Semester --}}
                <div>
                    <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">
                        Step 1: Select Semester *
                    </label>
                    <div class="relative">
                        <select name="semester" id="uploadSemester" required onchange="onSemesterChange(this)"
                                class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl px-4 py-4 text-on-surface text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary focus:outline-none transition-all cursor-pointer appearance-none">
                            
                            <option value="">-- Select Semester --</option>
                            
                            @php 
                                $activeSems = json_decode(optional($teacherProfile ?? null)->semester ?? '[]', true) ?? [];
                                if(!is_array($activeSems) && !empty($teacherProfile->semester)) {
                                    $activeSems = [$teacherProfile->semester];
                                }
                            @endphp

                            @if(empty($activeSems))
                                <option value="" disabled>⚠️ Please select your active semesters in Profile first!</option>
                            @else
                                @foreach ($activeSems as $sem)
                                    <option value="{{ $sem }}">Semester {{ $sem }}</option>
                                @endforeach
                            @endif
                        </select>
                        <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                    </div>
                    
                    @if(empty($activeSems))
                        <p class="mt-2 text-amber-400 text-xs flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">warning</span>
                            You haven't set any active semesters. Open "My Profile" to set them.
                        </p>
                    @endif

                    <p id="uploadHint" class="hidden mt-2 text-emerald-400 text-xs flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm" style="font-variation-settings:'FILL' 1">check_circle</span>
                        Semester selected! Now choose your files below.
                    </p>
                </div>

                {{-- Step 2: Files --}}
                <div id="uploadFileArea" class="upload-locked space-y-4">
                    <div>
                        <label class="block text-[10px] font-bold uppercase tracking-widest text-on-surface-variant mb-3">
                            Step 2: Select Files (Max 10MB each)
                        </label>
                        <label for="fileInput" class="border-2 border-dashed border-outline-variant/20 rounded-2xl p-8 flex flex-col items-center justify-center bg-surface-container-lowest hover:bg-surface-bright/20 transition-colors group cursor-pointer">
                            <span class="material-symbols-outlined text-4xl text-outline-variant group-hover:text-primary transition-colors mb-3">upload_file</span>
                            <p class="text-on-surface font-medium text-sm mb-1">Click to browse or drag and drop</p>
                            <p class="text-on-surface-variant text-xs">PDF, DOCX, XLSX, PPTX, TXT, PNG, JPG, MP3, MP4, ZIP, etc.</p>
                            <div id="fileCount" class="hidden mt-3 text-primary text-xs font-bold flex items-center gap-1">
                                <span class="material-symbols-outlined text-sm">attach_file</span>
                                <span id="fileCountText"></span> file(s) selected
                            </div>
                        </label>
                        <input type="file" name="files[]" id="fileInput" multiple disabled class="hidden" onchange="updateFileCount(this)">
                    </div>

                    <button type="submit" id="uploadBtn" disabled
                            class="w-full bg-gradient-to-r from-primary-dim to-primary py-4 rounded-xl font-bold text-on-primary-fixed shadow-lg shadow-primary-dim/20 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100">
                        <span class="material-symbols-outlined">rocket_launch</span> Upload Files
                    </button>
                </div>
            </form>
        </div>
    </section>

    {{-- ── Files List ── --}}
    <section>
        <div class="flex items-center justify-between mb-4 px-1">
            <h2 class="text-xl font-bold text-on-surface flex items-center gap-2">
                <span class="material-symbols-outlined text-tertiary">folder_shared</span> Your Uploaded Files
            </h2>
        </div>
        <div id="filesList" class="space-y-2">
            <div class="flex items-center justify-center p-10 text-on-surface-variant text-sm">
                <span class="material-symbols-outlined animate-spin mr-2">progress_activity</span> Loading files…
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<style>
    /* Add missing CSS for file-row hover animation */
    .file-row { transition: background 0.18s, transform 0.18s; }
    .file-row:hover { transform: translateX(4px); }
    .upload-locked { opacity:0.35; pointer-events:none; transition:opacity 0.25s; }
</style>
<script>
    // NAYA FUNCTION: Upload button ko sirf tab enable karega jab Semester aur File DONO select hon
    function checkUploadReady() {
        const sem = document.getElementById('uploadSemester').value;
        const files = document.getElementById('fileInput').files.length;
        const btn = document.getElementById('uploadBtn');
        
        if (sem && files > 0) {
            btn.disabled = false;
        } else {
            btn.disabled = true;
        }
    }

  /* ── Semester → unlock file area ── */
    function onSemesterChange(sel) {
        const area      = document.getElementById('uploadFileArea');
        const input     = document.getElementById('fileInput');
        const hint      = document.getElementById('uploadHint');

        if (sel.value) {
            area.classList.remove('upload-locked');
            input.disabled = false;
            hint.classList.remove('hidden');
            hint.classList.add('flex');
        } else {
            area.classList.add('upload-locked');
            input.disabled = true;
            hint.classList.add('hidden');
            hint.classList.remove('flex');
            input.value = '';
            document.getElementById('fileCount').classList.add('hidden');
        }
        
        // Semester change hone par check karo ki button on karna hai ya nahi
        checkUploadReady();
    }
    
    document.addEventListener('DOMContentLoaded', () => {
        onSemesterChange(document.getElementById('uploadSemester'));
    });

    /* ── File count ── */
    /* ── File count, Size & Type Validation ── */
    function updateFileCount(input) {
        const div  = document.getElementById('fileCount');
        const span = document.getElementById('fileCountText');
        
        const maxSizeMB = 10; 
        const maxSizeBytes = maxSizeMB * 1024 * 1024;
        
        // Tumhare controller me allowed extensions ki list
        const allowedExtensions = ['pdf', 'xlsx', 'docx', 'pptx', 'txt', 'png', 'jpg', 'jpeg', 'mp3', 'mp4', 'mkv', 'zip', 'rar', 'svg'];
        
        let hasError = false;
        let errorMessage = '';

        if (input.files.length > 0) {
            // Har ek select ki gayi file ko check karo
            for(let i = 0; i < input.files.length; i++) {
                const file = input.files[i];
                const fileExt = file.name.split('.').pop().toLowerCase();

                // Check 1: File Format / Extension sahi hai ya nahi?
                if (!allowedExtensions.includes(fileExt)) {
                    hasError = true;
                    errorMessage = `Error: "${file.name}" format unsupported hai.\nSirf ye formats allowed hain: ${allowedExtensions.join(', ')}`;
                    break;
                }

                // Check 2: File Size 10MB se bada to nahi hai?
                if(file.size > maxSizeBytes) {
                    hasError = true;
                    let currentMB = (file.size / (1024 * 1024)).toFixed(2);
                    errorMessage = `Error: "${file.name}" ka size bahut bada hai (${currentMB} MB).\nEk file ka maximum size ${maxSizeMB} MB allowed hai.`;
                    break; 
                }
            }

            // Agar koi bhi error mili, toh alert dikhao aur input clear kar do
            if(hasError) {
                alert(errorMessage);
                input.value = ''; // Files hta do taaki galat file server par na jaye
                div.classList.add('hidden');
                div.classList.remove('flex');
                checkUploadReady(); // Button ko disable kar dega
                return;
            }

            // Agar sab kuch ekdum sahi hai, toh files ka count dikhao
            span.textContent = input.files.length;
            div.classList.remove('hidden');
            div.classList.add('flex');
        } else {
            div.classList.add('hidden');
            div.classList.remove('flex');
        }

        // Button status check karo
        checkUploadReady();
    }

    /* ── Load file list ── */
    function loadFiles() {
        fetch('{{ route("teacher.files") }}')
            .then(r => r.json())
            .then(files => {
                const list = document.getElementById('filesList');
                if (!files.length) {
                    list.innerHTML = `
                    <div class="flex flex-col items-center justify-center p-14 bg-surface-container/30 border border-dashed border-outline-variant/10 rounded-2xl">
                        <span class="material-symbols-outlined text-5xl text-outline-variant/40 mb-3">folder_off</span>
                        <p class="text-on-surface-variant font-medium">No files uploaded yet</p>
                        <p class="text-outline text-xs mt-1">Upload your first study material above.</p>
                    </div>`;
                    return;
                }
                list.innerHTML = files.map(file => {
                    const icon     = getFileIcon(file.filename);
                    const date     = new Date(file.uploaded_at).toLocaleDateString('en-IN', {day:'2-digit',month:'short',year:'numeric'});
                    const semBadge = file.semester ? `<span class="bg-tertiary-container/20 text-tertiary-dim text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Sem ${file.semester}</span>` : '';
                    return `
                    <div class="file-row flex items-center justify-between p-4 bg-surface-container-high rounded-xl group hover:bg-surface-bright" id="file-row-${file.id}">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-surface-container-highest flex items-center justify-center text-xl flex-shrink-0">${icon}</div>
                            <div class="min-w-0">
                                <p class="font-semibold text-on-surface text-sm truncate">${file.filename}</p>
                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">${semBadge}<span class="text-on-surface-variant text-xs">${date}</span></div>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0 ml-3">
                            <a href="/teacher/file/${file.id}/preview" target="_blank" class="flex items-center gap-1 px-3 py-2 text-xs font-bold text-tertiary hover:bg-tertiary/10 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-sm">visibility</span><span class="hidden sm:inline">Preview</span>
                            </a>
                            <button onclick="deleteFile(${file.id}, this)" class="flex items-center gap-1 px-3 py-2 text-xs font-bold text-error hover:bg-error/10 rounded-lg transition-all">
                                <span class="material-symbols-outlined text-sm">delete</span><span class="hidden sm:inline">Delete</span>
                            </button>
                        </div>
                    </div>`;
                }).join('');
            })
            .catch(() => { document.getElementById('filesList').innerHTML = `<p class="text-center text-error text-sm py-6">⚠ Failed to load files. Please refresh.</p>`; });
    }

    function getFileIcon(filename) {
        const ext = filename.split('.').pop().toLowerCase();
        const map = { pdf:'📕', docx:'📝', doc:'📝', xlsx:'📊', xls:'📊', pptx:'📋', ppt:'📋', txt:'📄', png:'🖼️', jpg:'🖼️', jpeg:'🖼️', gif:'🖼️', svg:'🖼️', mp3:'🎵', m4a:'🎵', wav:'🎵', mp4:'🎬', mkv:'🎬', avi:'🎬', zip:'📦', rar:'📦' };
        return map[ext] || '📎';
    }

    function deleteFile(id, btn) {
        if (!confirm('Permanently delete this file?')) return;
        btn.disabled = true;
        btn.innerHTML = '<span class="material-symbols-outlined text-sm animate-spin">progress_activity</span>';
        fetch(`/teacher/file/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'X-Requested-With': 'XMLHttpRequest' }})
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById('file-row-' + id);
                row.style.opacity = '0';
                setTimeout(() => { row.remove(); if (!document.querySelector('.file-row')) loadFiles(); }, 380);
            } else {
                btn.disabled = false; btn.innerHTML = '<span class="material-symbols-outlined text-sm">delete</span><span class="hidden sm:inline">Delete</span>'; alert('Delete failed.');
            }
        })
        .catch(() => { btn.disabled = false; btn.innerHTML = '<span class="material-symbols-outlined text-sm">delete</span><span class="hidden sm:inline">Delete</span>'; alert('Network error.'); });
    }

    loadFiles();
</script>
@endpush