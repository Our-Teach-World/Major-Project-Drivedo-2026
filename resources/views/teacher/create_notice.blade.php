@extends('teacher.layout')

@section('page_title', 'Publish Notice')

@section('content')
<div class="max-w-3xl mx-auto">
    {{-- Page Header --}}
    <header class="mb-10 text-center">
        <h1 class="text-4xl font-extrabold text-on-surface tracking-tight mb-2">📢 Publish Notice</h1>
        <p class="text-on-surface-variant italic">Send real-time updates to your students across your assigned semesters.</p>
    </header>

    {{-- Flash Alerts --}}
    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-500/10 border border-emerald-500/20 rounded-xl flex items-center gap-3 text-emerald-400 animate-pulse">
            <span class="material-symbols-outlined text-lg" style="font-variation-settings:'FILL' 1">check_circle</span>
            <p class="text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    @if($errors->any())
        <div class="mb-6 p-4 bg-error/10 border border-error/20 rounded-xl flex items-start gap-3 text-error">
            <span class="material-symbols-outlined text-lg mt-0.5">error</span>
            <div class="text-sm space-y-0.5 font-semibold">
                @foreach($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        </div>
    @endif

    <div class="bg-surface-container rounded-3xl border border-outline-variant/10 shadow-2xl relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/10 to-transparent pointer-events-none"></div>
        <div class="p-8 relative">
            <form action="{{ route('teacher.notices.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <input type="hidden" name="target_role" value="student">
                
                {{-- Title --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                        <span class="w-1 h-1 bg-primary rounded-full"></span> Notice Title
                    </label>
                    <input type="text" name="title" required value="{{ old('title') }}"
                           class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl px-4 py-4 text-on-surface text-base focus:ring-2 focus:ring-primary/40 focus:border-primary focus:outline-none transition-all placeholder:text-on-surface-variant/30"
                           placeholder="e.g. Tomorrow's Data Structure class is cancelled.">
                </div>

                {{-- Content --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                        <span class="w-1 h-1 bg-primary rounded-full"></span> Notice Content
                    </label>
                    <textarea name="content" required rows="6"
                              class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl px-4 py-4 text-on-surface text-base focus:ring-2 focus:ring-primary/40 focus:border-primary focus:outline-none transition-all placeholder:text-on-surface-variant/30 resize-none"
                              placeholder="Type the detailed message for your students here...">{{ old('content') }}</textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Branch (Locked) --}}
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                            <span class="w-1 h-1 bg-primary rounded-full"></span> Target Department
                        </label>
                        <div class="w-full bg-surface border border-outline-variant/10 rounded-xl px-4 py-4 text-on-surface-variant text-sm font-bold flex items-center gap-2">
                            <span class="material-symbols-outlined text-sm text-primary">location_on</span>
                            {{ $teacherProfile->branch ?? 'Your Assigned Department' }}
                        </div>
                    </div>

                    {{-- Semester --}}
                    <div class="space-y-3">
                        <label class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                            <span class="w-1 h-1 bg-primary rounded-full"></span> Target Semester
                        </label>
                        <div class="relative">
                            <select name="target_semester" required
                                    class="w-full bg-surface-container-low border border-outline-variant/20 rounded-xl px-4 py-4 text-on-surface text-sm focus:ring-2 focus:ring-primary/40 focus:border-primary focus:outline-none transition-all cursor-pointer appearance-none font-bold">
                                <option value="">Select Target Semester</option>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem }}">Semester {{ $sem }}</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-on-surface-variant">expand_more</span>
                        </div>
                    </div>
                </div>

                {{-- Attachment --}}
                <div class="space-y-3">
                    <label class="block text-[10px] font-black uppercase tracking-widest text-on-surface-variant flex items-center gap-2">
                        <span class="w-1 h-1 bg-primary rounded-full"></span> Attachment (Optional)
                    </label>
                    <label for="attachment" class="border-2 border-dashed border-outline-variant/20 rounded-2xl p-8 flex flex-col items-center justify-center bg-surface hover:bg-primary/5 transition-all group cursor-pointer border-primary/10">
                        <span class="material-symbols-outlined text-4xl text-outline-variant/40 group-hover:text-primary transition-colors mb-3">attach_file</span>
                        <p id="fileName" class="text-on-surface font-medium text-sm mb-1">Click to attach a PDF or Image</p>
                        <p class="text-on-surface-variant text-[10px] font-bold uppercase tracking-wider">Max 5MB • PDF, JPG, PNG</p>
                    </label>
                    <input type="file" name="attachment" id="attachment" class="hidden" accept=".pdf,.jpg,.jpeg,.png" onchange="document.getElementById('fileName').innerText = this.files[0].name">
                </div>

                {{-- Submit Button --}}
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full bg-gradient-to-r from-primary/80 to-primary py-5 rounded-2xl font-black text-lg text-on-primary shadow-2xl shadow-primary/30 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-3 group">
                        <span class="material-symbols-outlined group-hover:rotate-12 transition-transform">send</span> 
                        PUBLISH & NOTIFY STUDENTS 🚀
                    </button>
                    <p class="mt-4 text-[10px] text-center font-bold uppercase tracking-[0.2em] text-on-surface-variant/40">Real-time Push Notification will be triggered</p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
