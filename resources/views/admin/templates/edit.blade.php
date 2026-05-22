@extends('admin.layouts.app')
@section('page_title', 'Edit Template')
@section('page-title', 'Edit Certificate Template')
@section('page-subtitle', 'Modify the HTML design and settings')

@section('content')
    <form method="POST" action="{{ route('admin.certchain.templates.update', $template) }}">
        @csrf @method('PUT')
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- Editor --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-6">
                    <h3 class="font-semibold text-on-surface font-bold mb-4">🎨 Template Details</h3>
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1.5">Template Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                                class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1.5">Type <span
                                    class="text-red-500">*</span></label>
                            <select name="type" required
                                class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                @foreach(['participation', 'achievement', 'completion', 'winner'] as $t)
                                    <option value="{{ $t }}" {{ $template->type === $t ? 'selected' : '' }}>{{ ucfirst($t) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-on-surface mb-1.5">Border Style</label>
                            <select name="border_style"
                                class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                                @foreach(['classic', 'modern', 'minimal'] as $b)
                                    <option value="{{ $b }}" {{ $template->border_style === $b ? 'selected' : '' }}>
                                        {{ ucfirst($b) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-3 mt-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }} class="rounded border-gray-300">
                                <span class="text-sm text-on-surface">Active (available for use)</span>
                            </label>
                        </div>
                    </div>

                    <label class="block text-sm font-medium text-on-surface mb-1.5">HTML Template <span
                            class="text-red-500">*</span></label>
                    <textarea name="html_content" rows="22" required id="htmlEditor"
                        class="w-full border border-outline-variant/20 rounded-lg px-3 py-2.5 text-xs font-mono focus:outline-none focus:ring-2 focus:ring-primary resize-y">{{ old('html_content', $template->html_content) }}</textarea>
                    <p class="text-xs text-on-surface-variant opacity-70 mt-1">Use <code
                            class="bg-gray-100 px-1 rounded">&#123;&#123;student_name&#125;&#125;</code>, <code
                            class="bg-gray-100 px-1 rounded">&#123;&#123;event_name&#125;&#125;</code> etc. as placeholders.
                    </p>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-4">
                <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-lg p-5">
                    <h4 class="font-semibold text-on-surface font-bold mb-3">📋 Available Placeholders</h4>
                    <p class="text-xs text-on-surface-variant opacity-70 mb-2">Click any placeholder to insert it at cursor position in the
                        editor.</p>
                    <div class="text-xs space-y-1 font-mono text-blue-700">
                        @php
                            $placeholders = [
                                'student_name',
                                'enrollment_number',
                                'student_branch',
                                'student_year',
                                'event_name',
                                'event_date',
                                'event_type',
                                'venue',
                                'achievement',
                                'description',
                                'issued_date',
                                'issued_by',
                                'issuer_designation',
                                'certificate_id',
                                'block_hash',
                                'college_name'
                            ];
                        @endphp
                        @foreach($placeholders as $ph)
                        @php $phText = '{{' . $ph . '}}'; @endphp
                        <p class="bg-primary/5 px-2 py-1 rounded cursor-pointer hover:bg-primary/10"
                            onclick="insertPlaceholder(this.dataset.ph)"
                            data-ph="{{ $phText }}">
                                &#123;&#123;{{ $ph }}&#125;&#125;
                        </p>
                        @endforeach 
                        @php $qrText = '{{{qr_code}}}'; @endphp
                        <p class="bg-yellow-50 px-2 py-1 rounded text-yellow-700 cursor-pointer hover:bg-yellow-100"
                            onclick="insertPlaceholder(this.dataset.ph)"
                            data-ph="{{ $qrText }}">
                                &#123;&#123;&#123;qr_code&#125;&#125;&#125; (triple braces)
                        </p>
                    </div>
                </div>

                <div class="flex flex-col gap-2">
                    <a href="{{ route('admin.certchain.templates.preview', $template) }}" target="_blank"
                        class="w-full text-center py-2.5 border border-outline-variant/20 rounded-xl text-sm text-on-surface-variant hover:bg-surface-container-low transition">
                        👁 Preview Template
                    </a>
                    <button type="submit"
                        class="w-full py-2.5 bg-gradient-to-r from-primary to-primary/80 text-white rounded-xl font-semibold text-sm hover:from-blue-800 transition-all shadow-lg">
                        💾 Save Changes
                    </button>
                    <a href="{{ route('admin.certchain.templates.index') }}"
                        class="text-center text-sm text-on-surface-variant opacity-70 hover:text-on-surface-variant">Cancel</a>
                </div>
            </div>
        </div>
    </form>

    @push('scripts')
        <script>
            function insertPlaceholder(text) {
                const ta = document.getElementById('htmlEditor');
                const start = ta.selectionStart;
                const end = ta.selectionEnd;
                ta.value = ta.value.substring(0, start) + text + ta.value.substring(end);
                ta.selectionStart = ta.selectionEnd = start + text.length;
                ta.focus();
            }
        </script>
    @endpush
@endsection
