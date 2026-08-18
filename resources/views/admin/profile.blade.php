@extends('admin.layouts.app')
@section('title', 'My Profile - CampusCore')
@section('header_title', 'HOD Profile Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Success/Error Alerts --}}
    @if(session('success'))
        <div class="alert alert-success">
            ✨ {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            🚨 {{ $errors->first() }}
        </div>
    @endif

    <div class="grid md:grid-cols-3 gap-8">
        {{-- Left: Profile Card --}}
        <div class="md:col-span-1">
            <div class="card text-center flex flex-col items-center">
                <div class="relative group mb-4">
                    @if($admin->image_path)
                        <img src="{{ asset($admin->image_path) }}" class="w-32 h-32 rounded-full object-cover border-4 border-[#253745] shadow-lg" alt="HOD Profile Image">
                    @else
                        <div class="w-32 h-32 rounded-full bg-[#253745] text-[#CCD0CF] text-4xl font-extrabold flex items-center justify-center border-4 border-white shadow-lg">
                            {{ strtoupper(substr($admin->name ?? $admin->username, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h3 class="font-extrabold text-xl text-[#06141B] leading-tight mb-1">{{ $admin->name ?? $admin->username }}</h3>
                <p class="text-xs text-[#253745] bg-[#F2F4F3] px-3 py-1 rounded-full font-bold uppercase tracking-wider mb-4 inline-block">{{ $admin->role }}</p>

                <div class="w-full text-left pt-4 border-t border-gray-100 space-y-3 text-sm">
                    <div>
                        <span class="text-gray-400 block text-xs">Username</span>
                        <span class="font-semibold text-gray-800">{{ $admin->username }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Email Address</span>
                        <span class="font-semibold text-gray-800">{{ $admin->email ?? '—' }}</span>
                    </div>
                    <div>
                        <span class="text-gray-400 block text-xs">Branch / Department</span>
                        <span class="font-semibold text-gray-800 uppercase">{{ $admin->branch ?? 'All Branches' }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Settings Form --}}
        <div class="md:col-span-2">
            <div class="card">
                <h3 class="font-extrabold text-xl text-[#06141B] mb-6 flex items-center gap-2">
                    <span>⚙️</span> Edit Profile Details
                </h3>

                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Display Name</label>
                            <input type="text" name="name" value="{{ old('name', $admin->name ?? $admin->username) }}" required 
                                placeholder="Enter your full name" 
                                class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:outline-none focus:ring-2 focus:ring-[#253745] focus:border-[#253745] transition-all bg-gray-50/50">
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Profile Image</label>
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <input type="file" name="image" accept="image/*"
                                        class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-[#253745]/10 file:text-[#253745] hover:file:bg-[#253745]/20 file:cursor-pointer cursor-pointer border border-gray-200 rounded-xl p-1 bg-gray-50/50">
                                </div>
                            </div>
                            <span class="text-xs text-gray-400 mt-2 block">Accepted formats: JPG, PNG, GIF. Max size: 2MB.</span>
                        </div>

                        <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                            <a href="{{ route('admin.dashboard') }}" class="btn">Cancel</a>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
