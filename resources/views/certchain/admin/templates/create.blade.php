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
        <div class="card p-6">
            <h3 class="font-semibold text-gray-800 mb-4">Template Info</h3>
            <div class="grid md:grid-cols-3 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Template Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $template->name ?? '') }}" required
                        class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="e.g. Participation Certificate">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['participation','achievement','completion','winner'] as $t)
                        <option value="{{ $t }}" {{ old('type', $template->type ?? '') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Border Style</label>
                    <select name="border_style" class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                        @foreach(['classic','modern','minimal'] as $b)
                        <option value="{{ $b }}" {{ old('border_style', $template->border_style ?? 'classic') === $b ? 'selected' : '' }}>{{ ucfirst($b) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 mt-4">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                        {{ old('is_active', $template->is_active ?? true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-blue-600">
                    <label for="is_active" class="text-sm text-gray-700">Active (available for issuing)</label>
                </div>
            </div>
        </div>

        <div class="card p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-gray-800">HTML Content <span class="text-red-500">*</span></h3>
                <button type="button" onclick="previewTemplate()" class="text-xs text-blue-600 hover:underline border border-blue-200 px-3 py-1 rounded-lg">👁 Preview</button>
            </div>
            <textarea name="html_content" id="htmlEditor" rows="22" required
                class="w-full border border-gray-200 rounded-lg px-3 py-2.5 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
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

        <div class="flex flex-col gap-2">
            <button type="submit" class="btn-primary text-center">
                {{ isset($template) ? '💾 Update Template' : '✅ Save Template' }}
            </button>
            <a href="{{ route('admin.certchain.templates.index') }}" class="text-center text-sm text-gray-400 hover:text-gray-600">Cancel</a>
        </div>
    </div>
</div>
</form>

{{-- Preview iframe modal --}}
<div id="previewModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl w-full max-w-5xl h-[80vh] flex flex-col">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="font-semibold">Template Preview (Sample Data)</h3>
            <button onclick="closePreview()" class="text-gray-400 hover:text-gray-600 text-xl">✕</button>
        </div>
        <iframe id="previewFrame" class="flex-1 w-full rounded-b-xl"></iframe>
    </div>
</div>

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
        // We use string concatenation here to prevent Blade from parsing these as directives
        const p3 = '{' + '{' + '{' + k + '}' + '}' + '}';
        const p2 = '{' + '{' + k + '}' + '}';
        rendered = rendered.replaceAll(p3, v).replaceAll(p2, v);
    });
    const frame = document.getElementById('previewFrame');
    frame.srcdoc = rendered;
    document.getElementById('previewModal').classList.remove('hidden');
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}
</script>
@endpush
@endsection
