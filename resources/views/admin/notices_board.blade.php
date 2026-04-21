@extends('admin.layouts.app')

@section('title', 'Staff Notice Board - EduShare Admin')
@section('header_title', '📢 Staff Notice Board')

@section('content')
<div style="background: #fff; border: 2px solid #000; box-shadow: 6px 6px 0px #000; padding: 30px; border-radius: 8px; margin-bottom: 40px;">
    <h2 style="font-weight: 900; margin-bottom: 5px; font-size: 2.2rem;">📢 Internal Announcements</h2>
    <p style="color: #555; font-weight: 600;">Feed of all official communications for HODs and Faculty.</p>
    
    <div id="adminStaffNoticeList" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 20px; margin-top: 30px;">
        <div style="padding: 20px; text-align: center; color: #666; font-weight: 700;">
            Loading internal announcements...
        </div>
    </div>
</div>

@push('scripts')
<script>
    function loadAdminStaffNotices() {
        const list = document.getElementById('adminStaffNoticeList');
        // Determine the correct data source based on actual session role
        const fetchUrl = "{{ ($role === 'principal') ? route('principal.notices.index') : route('admin.notices.index') }}";

        fetch(fetchUrl)
            .then(r => r.json())
            .then(notices => {
                if (!notices.length) {
                    list.innerHTML = `<p style="grid-column: 1/-1; text-align: center; color: #666; font-weight: 700; padding: 40px; border: 2px dashed #000;">No internal announcements found.</p>`;
                    return;
                }

                list.innerHTML = notices.map(n => `
                    <div style="padding: 25px; border: 2px solid #000; border-radius: 5px; background: #fff; box-shadow: 6px 6px 0px #000; display: flex; flex-direction: column;">
                        <div style="font-size: 0.75rem; font-weight: 900; color: #666; text-transform: uppercase; margin-bottom: 8px;">
                            📅 ${new Date(n.created_at).toLocaleDateString()}
                        </div>
                        <div style="font-weight: 900; font-size: 1.3rem; margin-bottom: 12px; line-height: 1.2;">${n.title}</div>
                        <div style="font-size: 0.95rem; margin-bottom: 20px; line-height: 1.5; color: #333; flex-grow: 1;">${n.content}</div>
                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.8rem; font-weight: 800; padding-top: 15px; border-top: 2px solid #eee;">
                            <span style="background: #000; color: #fff; padding: 4px 10px; border-radius: 4px;">👤 By: 
                                ${(function() {
                                    const role = (n.creator?.role || '').toLowerCase().trim();
                                    if (role === 'principal') return 'Principal';
                                    if (role === 'hod') return 'HOD';
                                    if (role === 'teacher') return `Prof. ${n.creator?.teacher_profile?.display_name || n.creator?.username}`;
                                    return 'Admin';
                                })()}
                            </span>
                            ${n.attachment_path ? `<a href="/${n.attachment_path}" target="_blank" style="color: #2563eb; text-decoration: underline;">📎 Attachment</a>` : ''}
                        </div>
                    </div>`).join('');
            });
    }

    document.addEventListener('DOMContentLoaded', loadAdminStaffNotices);
</script>
@endpush
@endsection
