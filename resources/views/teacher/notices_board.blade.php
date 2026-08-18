@extends('teacher.layout')

@section('page_title', 'College Notice Board')

@section('content')
<div class="max-w-6xl mx-auto">
    <header class="mb-10">
        <h1 class="text-4xl font-extrabold text-on-surface tracking-tight mb-2">Notice Board</h1>
        <p class="text-on-surface-variant italic">Stay updated with the latest college-wide and faculty announcements.</p>
    </header>

    <div id="staffNoticeList" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="p-6 bg-surface-container-low border border-outline-variant/10 rounded-2xl text-on-surface-variant text-sm flex items-center justify-center animate-pulse">
            <span class="material-symbols-outlined mr-2">progress_activity</span> Loading notices...
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadStaffNotices() {
        const list = document.getElementById('staffNoticeList');
        const fetchUrl = "{{ route('teacher.notices.index') }}";

        fetch(fetchUrl)
            .then(r => r.json())
            .then(notices => {
                if (!notices.length) {
                    list.innerHTML = `
                    <div class="col-span-full p-12 bg-surface-container/30 border border-dashed border-outline-variant/10 rounded-3xl text-center">
                        <span class="material-symbols-outlined text-4xl text-on-surface-variant/20 mb-3">inbox</span>
                        <p class="text-on-surface-variant text-sm font-medium">No announcements found in your feed.</p>
                    </div>`;
                    return;
                }

                list.innerHTML = notices.map(n => `
                    <div class="p-6 bg-surface-container-high border border-outline-variant/10 rounded-2xl hover:border-primary/30 transition-all group relative overflow-hidden flex flex-col shadow-xl">
                        <div class="absolute top-0 right-0 w-24 h-24 bg-primary/5 rounded-bl-full -mr-12 -mt-12 flex items-center justify-center pt-8 pl-8 text-primary/10 group-hover:bg-primary/10 transition-colors">
                            <span class="material-symbols-outlined text-4xl">campaign</span>
                        </div>
                        <div class="flex items-center gap-2 mb-4">
                            <span class="text-on-surface-variant text-[10px] font-bold uppercase tracking-widest bg-surface-bright px-2 py-1 rounded">
                                ${new Date(n.created_at).toLocaleDateString()}
                            </span>
                        </div>
                        <h3 class="text-xl font-black text-on-surface mb-3 leading-tight">${n.title}</h3>
                        <p class="text-on-surface-variant text-sm mb-6 leading-relaxed flex-grow">${n.content}</p>
                        <div class="flex items-center justify-between pt-4 border-t border-outline-variant/5">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-primary/20 flex items-center justify-center text-xs">👤</div>
                                <div class="flex flex-col">
                                    <span class="text-[10px] font-bold text-primary uppercase">Posted By</span>
                                    <span class="text-xs font-bold text-on-surface/90">
                                        ${(function() {
                                            const role = (n.creator?.role || '').toLowerCase().trim();
                                            if (role === 'principal') return 'Principal';
                                            if (role === 'hod') return 'HOD';
                                            if (role === 'teacher') return `Prof. ${n.creator?.teacher_profile?.display_name || n.creator?.username}`;
                                            return 'Admin';
                                        })()}
                                    </span>
                                </div>
                            </div>
                            ${n.attachment_path ? `
                                <a href="/${n.attachment_path}" target="_blank" class="flex items-center gap-1 text-xs font-bold text-primary hover:text-white bg-primary/10 hover:bg-primary px-3 py-2 rounded-lg transition-all">
                                    <span class="material-symbols-outlined text-sm">attach_file</span> VIEW
                                </a>
                            ` : ''}
                        </div>
                    </div>`).join('');
            });
    }
    document.addEventListener('DOMContentLoaded', loadStaffNotices);
</script>
@endpush
@endsection
