@extends('admin.layouts.app')
@section('title', 'Edit Template')
@section('page-title', 'Edit Certificate Template')
@section('page-subtitle', 'Modify the HTML design and settings')

@section('content')
    <form method="POST" action="{{ route('admin.certchain.templates.update', $template) }}">
        @csrf @method('PUT')
        <div class="grid lg:grid-cols-3 gap-6">

            {{-- Editor --}}
            <div class="lg:col-span-2 space-y-5">
                <div class="bg-white border-2 border-black p-6 rounded-xl shadow-[4px_4px_0px_#000]">
                    <h3 class="font-bold text-gray-900 mb-4">🎨 TEMPLATE DETAILS</h3>
                    <div class="grid md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block text-xs font-bold text-black uppercase mb-1.5">Template Name <span
                                    class="text-red-500">*</span></label>
                            <input type="text" name="name" value="{{ old('name', $template->name) }}" required
                                class="w-full border-2 border-black rounded-lg px-3 py-2.5 text-sm focus:outline-none shadow-[2px_2px_0px_#000]">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-black uppercase mb-1.5">Type <span
                                    class="text-red-500">*</span></label>
                            <select name="type" required
                                class="w-full border-2 border-black rounded-lg px-3 py-2.5 text-sm focus:outline-none shadow-[2px_2px_0px_#000]">
                                @foreach(['participation', 'achievement', 'completion', 'winner'] as $t)
                                    <option value="{{ $t }}" {{ $template->type === $t ? 'selected' : '' }}>{{ ucfirst($t) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-black uppercase mb-1.5">Border Style</label>
                            <select name="border_style"
                                class="w-full border-2 border-black rounded-lg px-3 py-2.5 text-sm focus:outline-none shadow-[2px_2px_0px_#000]">
                                @foreach(['classic', 'modern', 'minimal'] as $b)
                                    <option value="{{ $b }}" {{ $template->border_style === $b ? 'selected' : '' }}>
                                        {{ ucfirst($b) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex items-center gap-3 mt-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="is_active" value="1" {{ $template->is_active ? 'checked' : '' }} class="rounded border-2 border-black w-5 h-5 text-black">
                                <span class="text-xs font-bold text-black uppercase">Active</span>
                            </label>
                        </div>
                    </div>

                    <label class="block text-xs font-bold text-black uppercase mb-1.5">HTML TEMPLATE <span
                            class="text-red-500">*</span></label>
                    <textarea name="html_content" rows="22" required id="htmlEditor"
                        class="w-full border-2 border-black rounded-lg px-3 py-2.5 text-xs font-mono focus:outline-none shadow-[2px_2px_0px_#000] resize-y">{{ old('html_content', $template->html_content) }}</textarea>
                    <p class="text-[10px] text-gray-500 font-bold uppercase mt-1">Use <code
                            class="bg-gray-100 px-1 rounded">&#123;&#123;student_name&#125;&#125;</code>, <code
                            class="bg-gray-100 px-1 rounded">&#123;&#123;event_name&#125;&#125;</code> etc.</p>
                </div>
            </div>

        {{-- Sidebar --}}
        <div class="space-y-4">
            <div class="bg-white border-2 border-black p-5 rounded-xl shadow-[4px_4px_0px_#000]">
                <h4 class="font-bold text-black uppercase mb-3">📋 PLACEHOLDERS</h4>
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
                        $o3.'qr_code'.$c3          => 'QR code',
                    ];
                @endphp
                <div class="text-xs space-y-1 font-mono text-blue-700 max-h-60 overflow-y-auto">
                    @foreach($placeholders as $ph => $desc)
                    <p class="bg-blue-50 px-2 py-1 rounded cursor-pointer hover:bg-blue-100 flex justify-between" 
                       onclick="insertPlaceholder('{{ $ph }}')">
                        <span>{{ $ph }}</span>
                        <span class="text-[10px] text-gray-400">{{ $desc }}</span>
                    </p>
                    @endforeach
                </div>
            </div>

                    <button type="button" onclick="previewTemplate()"
                        class="w-full text-center py-2.5 border-2 border-black bg-blue-100 rounded-xl text-sm font-bold text-black hover:bg-blue-200 transition-all mb-2 shadow-[2px_2px_0px_#000]">
                        👁 LIVE PREVIEW
                    </button>
                    <a href="{{ route('admin.certchain.templates.preview', $template) }}" target="_blank"
                        class="w-full block text-center py-2.5 border-2 border-black rounded-xl text-sm font-bold text-gray-600 hover:bg-gray-50 transition-all">
                        👁 VIEW SAVED
                    </a>
                    <button type="submit"
                        class="w-full py-4 bg-black text-white border-2 border-black rounded-xl font-black text-sm uppercase tracking-widest hover:bg-gray-800 transition-all shadow-[6px_6px_0px_#3b82f6] mt-4">
                        SAVE CHANGES
                    </button>
                    <a href="{{ route('admin.certchain.templates.index') }}"
                        class="block text-center text-xs font-bold text-gray-500 hover:text-black uppercase tracking-wider mt-3">Cancel</a>
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