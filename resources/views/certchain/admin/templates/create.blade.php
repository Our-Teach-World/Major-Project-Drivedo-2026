@extends('admin.layouts.app')
@section('title', isset($template) ? 'Edit Template' : 'Create Template')
@section('page-title', isset($template) ? 'Edit Template' : 'Create Certificate Template')
@section('page-subtitle','Design your certificate HTML. Use @{{placeholders}} for dynamic content.')

@section('content')
<form method="POST" action="{{ isset($template) ? route('admin.certchain.templates.update', $template) : route('admin.certchain.templates.store') }}">
@csrf
@if(isset($template)) @method('PUT') @endif

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Left: Editor --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-white border-2 border-black p-6 rounded-xl shadow-[4px_4px_0px_#000]">
            <h3 class="font-bold text-gray-900 mb-4">TEMPLATE INFO</h3>
            <div class="grid md:grid-cols-3 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-black uppercase mb-1.5">Template Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $template->name ?? '') }}" required
                        class="w-full border-2 border-black rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 shadow-[2px_2px_0px_#000]"
                        placeholder="e.g. Participation Certificate">
                </div>
                <div>
                    <label class="block text-xs font-bold text-black uppercase mb-1.5">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full border-2 border-black rounded-lg px-3 py-2.5 text-sm focus:outline-none shadow-[2px_2px_0px_#000]">
                        @foreach(['participation','achievement','completion','winner'] as $t)
                        <option value="{{ $t }}" {{ old('type', $template->type ?? '') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-black uppercase mb-1.5">Border Style</label>
                    <select name="border_style" class="w-full border-2 border-black rounded-lg px-3 py-2.5 text-sm focus:outline-none shadow-[2px_2px_0px_#000]">
                        @foreach(['classic','modern','minimal'] as $b)
                        <option value="{{ $b }}" {{ old('border_style', $template->border_style ?? 'classic') === $b ? 'selected' : '' }}>{{ ucfirst($b) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 mt-4">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                        {{ old('is_active', $template->is_active ?? true) ? 'checked' : '' }}
                        class="rounded border-2 border-black text-black w-5 h-5">
                    <label for="is_active" class="text-sm font-bold text-black uppercase">Active</label>
                </div>
            </div>
        </div>

        <div class="bg-white border-2 border-black p-6 rounded-xl shadow-[4px_4px_0px_#000]">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-bold text-gray-900">HTML CONTENT <span class="text-red-500">*</span></h3>
                <button type="button" onclick="previewTemplate()" class="text-xs font-bold text-black bg-blue-100 hover:bg-blue-200 border-2 border-black px-4 py-1.5 rounded-lg transition-all shadow-[2px_2px_0px_#000]">
                    👁 LIVE PREVIEW
                </button>
            </div>
            <textarea name="html_content" id="htmlEditor" rows="22" required
                class="w-full border-2 border-black rounded-lg px-3 py-2.5 text-xs font-mono focus:outline-none shadow-[2px_2px_0px_#000] resize-none"
                placeholder="Paste your certificate HTML here...">{{ old('html_content', $template->html_content ?? $defaultHtml ?? '') }}</textarea>
        </div>
    </div>

    {{-- Right: Placeholders Guide --}}
    <div class="space-y-4">
        <div class="card p-5">
            <h4 class="font-semibold text-gray-800 mb-3">📋 Available Placeholders</h4>
            <p class="text-xs text-gray-500 mb-3">Click to copy. Use these in your HTML template.</p>
            @php
            $o = '{' . '{'; $c = '}' . '}';
            $o3 = '{' . '{' . '{'; $c3 = '}' . '}' . '}';
            $placeholders = [
                $o.'student_name'.$c       => 'Full Name',
                $o.'enrollment_number'.$c  => 'Enrollment No',
                $o.'student_branch'.$c     => 'Branch/Dept',
                $o.'student_year'.$c       => 'Year',
                $o.'event_name'.$c         => 'Event Name',
                $o.'event_date'.$c         => 'Event Date',
                $o.'event_type'.$c         => 'Event Type',
                $o.'venue'.$c              => 'Venue',
                $o.'achievement'.$c        => 'Achievement',
                $o.'description'.$c        => 'Description',
                $o.'issued_date'.$c        => 'Issued Date',
                $o.'issued_by'.$c          => 'Issuer Name',
                $o.'issuer_designation'.$c => 'Issuer Job Title',
                $o.'certificate_id'.$c     => 'Certificate ID',
                $o.'block_hash'.$c         => 'Blockchain hash',
                $o.'college_name'.$c       => 'College name',
                $o3.'qr_code'.$c3          => 'QR code SVG',
            ];
            @endphp
            <div class="space-y-1.5 max-h-80 overflow-y-auto">
                @foreach($placeholders as $ph => $desc)
                <div class="flex items-center justify-between gap-2 p-1.5 rounded hover:bg-gray-50 cursor-pointer" onclick="copyPlaceholder('{{ $ph }}')">
                    <code class="text-xs bg-blue-50 text-blue-700 px-1.5 py-0.5 rounded">{{ $ph }}</code>
                    <span class="text-xs text-gray-400 flex-1 text-right">{{ $desc }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="card p-5">
            <h4 class="font-semibold text-gray-800 mb-2">💡 Tips</h4>
            <ul class="text-xs text-gray-500 space-y-1.5">
                <li>• Use A4 landscape: <code class="bg-gray-100 px-1 rounded">297mm × 210mm</code></li>
                <li>• Use <code class="bg-gray-100 px-1 rounded">&#123;&#123;&#123;qr_code&#125;&#125;&#125;</code> (triple braces) for QR</li>
                <li>• Google Fonts work via @import</li>
                <li>• Test with Preview before saving</li>
            </ul>
        </div>

        <div class="flex flex-col gap-3">
            <button type="submit" class="w-full py-4 bg-black text-white border-2 border-black rounded-xl font-black text-sm uppercase tracking-widest hover:bg-gray-800 transition-all shadow-[6px_6px_0px_#3b82f6]">
                {{ isset($template) ? 'Update Template' : 'Save Template' }}
            </button>
            <a href="{{ route('admin.certchain.templates.index') }}" class="text-center text-xs font-bold text-gray-500 hover:text-black uppercase tracking-wider">Cancel</a>
        </div>
    </div>
</div>
</form>


@push('scripts')
<script>
function copyPlaceholder(text) {
    navigator.clipboard.writeText(text);
    const editor = document.getElementById('htmlEditor');
    const pos = editor.selectionStart;
    const val = editor.value;
    editor.value = val.substring(0, pos) + text + val.substring(pos);
    editor.focus();
}

function previewTemplate() {
    const html = document.getElementById('htmlEditor').value;
    const sample = {
        student_name: 'Rahul Sharma', enrollment_number: '0801CS211001',
        student_branch: 'Computer Science', student_year: '3rd Year',
        event_name: 'National Tech Symposium 2024', event_date: '15 Nov 2024',
        event_type: 'Symposium', venue: 'Main Auditorium',
        achievement: '1st Prize', description: 'for outstanding performance',
        issued_date: new Date().toLocaleDateString('en-GB', {day:'2-digit',month:'short',year:'numeric'}),
        issued_by: 'Dr. Priya Singh', issuer_designation: 'HOD, Computer Science',
        certificate_id: 'CERT-2024-AB1234', block_hash: 'a3f8c1e2b7d4...',
        college_name: 'Your College of Engineering', qr_code: ''
    };
    let rendered = html;
    Object.entries(sample).forEach(([k,v]) => {
        const p3 = '{' + '{' + '{' + k + '}' + '}' + '}';
        const p2 = '{' + '{' + k + '}' + '}';
        rendered = rendered.replaceAll(p3, v).replaceAll(p2, v);
    });
    
    const previewWindow = window.open('', '_blank');
    previewWindow.document.open();
    previewWindow.document.write(rendered);
    previewWindow.document.close();
}
</script>
@endpush
@endsection
