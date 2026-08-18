@extends('admin.layouts.app')
@section('page_title', isset($template) ? 'Edit Template' : 'Create Template')
@section('page-title', isset($template) ? 'Edit Template' : 'Create Certificate Template')
@section('page-subtitle','Design your certificate HTML. Use {{placeholders}} for dynamic content.')

@section('content')
<form method="POST" action="{{ isset($template) ? route('admin.certchain.templates.update', $template) : route('admin.certchain.templates.store') }}">
@csrf
@if(isset($template)) @method('PUT') @endif

<div class="grid lg:grid-cols-3 gap-6">
    {{-- Left: Editor --}}
    <div class="lg:col-span-2 space-y-5">
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
            <h3 class="font-semibold text-on-surface font-bold mb-4">Template Info</h3>
            <div class="grid md:grid-cols-3 gap-4 mb-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Template Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $template->name ?? '') }}" required
                        class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="e.g. Participation Certificate">
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Type <span class="text-red-500">*</span></label>
                    <select name="type" required class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        @foreach(['participation','achievement','completion','winner'] as $t)
                        <option value="{{ $t }}" {{ old('type', $template->type ?? '') === $t ? 'selected' : '' }}>{{ ucfirst($t) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-on-surface mb-1.5">Border Style</label>
                    <select name="border_style" class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        @foreach(['classic','modern','minimal'] as $b)
                        <option value="{{ $b }}" {{ old('border_style', $template->border_style ?? 'classic') === $b ? 'selected' : '' }}>{{ ucfirst($b) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2 mt-4">
                    <input type="checkbox" name="is_active" value="1" id="is_active"
                        {{ old('is_active', $template->is_active ?? true) ? 'checked' : '' }}
                        class="rounded border-gray-300 text-primary">
                    <label for="is_active" class="text-sm text-on-surface">Active (available for issuing)</label>
                </div>
            </div>
        </div>

        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-on-surface font-bold">HTML Content <span class="text-red-500">*</span></h3>
                <button type="button" onclick="previewTemplate()" class="text-xs text-primary hover:underline border border-blue-200 px-3 py-1 rounded-lg">👁 Preview</button>
            </div>
            <textarea name="html_content" id="htmlEditor" rows="22" required
                class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-primary resize-none"
                placeholder="Paste your certificate HTML here...">{{ old('html_content', $template->html_content ?? $defaultHtml ?? '') }}</textarea>
        </div>
    </div>

    {{-- Right: Placeholders Guide --}}
    <div class="space-y-4">
        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5">
            <h4 class="font-semibold text-on-surface font-bold mb-3">📋 Available Placeholders</h4>
            <p class="text-xs text-on-surface-variant mb-3">Click to copy. Use these in your HTML template.</p>
            @php
            $placeholders = [
                '{{student_name}}'       => 'Student full name',
                '{{enrollment_number}}'  => 'Student enrollment no.',
                '{{student_branch}}'     => 'Branch/department',
                '{{student_year}}'       => 'Year (1st, 2nd...)',
                '{{event_name}}'         => 'Name of the event',
                '{{event_date}}'         => 'Event date',
                '{{event_type}}'         => 'Type of event',
                '{{venue}}'              => 'Event venue',
                '{{achievement}}'        => 'Achievement type',
                '{{description}}'        => 'Custom description',
                '{{issued_date}}'        => 'Date of issue',
                '{{issued_by}}'          => 'Issuing faculty name',
                '{{issuer_designation}}' => 'Faculty designation',
                '{{certificate_id}}'     => 'Unique cert ID',
                '{{block_hash}}'         => 'Blockchain hash',
                '{{college_name}}'       => 'College name',
                '{{{qr_code}}}'          => 'QR code SVG',
            ];
            @endphp
            <div class="space-y-1.5 max-h-80 overflow-y-auto">
                @foreach($placeholders as $ph => $desc)
                <div class="flex items-center justify-between gap-2 p-1.5 rounded hover:bg-surface-container-low cursor-pointer" onclick="copyPlaceholder('{{ $ph }}')">
                    <code class="text-xs bg-primary/5 text-blue-700 px-1.5 py-0.5 rounded">{{ $ph }}</code>
                    <span class="text-xs text-on-surface-variant opacity-70 flex-1 text-right">{{ $desc }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5">
            <h4 class="font-semibold text-on-surface font-bold mb-2">💡 Tips</h4>
            <ul class="text-xs text-on-surface-variant space-y-1.5">
                <li>• Use A4 landscape: <code class="bg-gray-100 px-1 rounded">297mm × 210mm</code></li>
                <li>• Use <code class="bg-gray-100 px-1 rounded">@{{{qr_code}}}</code> (triple braces for QR code)</li>
                <li>• Google Fonts work via @import</li>
                <li>• Test with Preview before saving</li>
            </ul>
        </div>

        <div class="flex flex-col gap-2">
            <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded-xl font-bold hover:scale-[1.02] transition-all text-center">
                {{ isset($template) ? '💾 Update Template' : '✅ Save Template' }}
            </button>
            <a href="{{ route('admin.certchain.templates.index') }}" class="text-center text-sm text-on-surface-variant opacity-70 hover:text-on-surface-variant">Cancel</a>
        </div>
    </div>
</div>
</form>

{{-- Preview iframe modal --}}
<div id="previewModal" class="hidden fixed inset-0 bg-black/60 z-50 flex items-center justify-center p-4">
    <div class="bg-surface rounded-xl w-full max-w-5xl h-[80vh] flex flex-col">
        <div class="flex items-center justify-between p-4 border-b">
            <h3 class="font-semibold">Template Preview (Sample Data)</h3>
            <button onclick="closePreview()" class="text-on-surface-variant opacity-70 hover:text-on-surface-variant text-xl">✕</button>
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
        rendered = rendered.replaceAll(`@{{${k}}}`, v).replaceAll(`@{${k}}`, v);
    });
    const frame = document.getElementById('previewFrame');
    frame.srcdoc = rendered;
    document.getElementById('previewModal').classList.remove('hidden');
}

function closePreview() {
    document.getElementById('previewModal').classList.add('hidden');
}
</script>
@endverbatim
@endpush
@endsection
