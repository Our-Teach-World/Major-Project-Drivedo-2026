@extends('alumni.layouts.app')

@section('title', 'My Profile - Alumni Dashboard')
@section('header_title', '👤 My Profile')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="background: #ffffff; border-radius: 32px; padding: 40px; border: 1px solid rgba(6, 20, 27, 0.05); box-shadow: 0 10px 40px rgba(6, 20, 27, 0.05);">
        <div style="display: flex; align-items: center; gap: 30px; margin-bottom: 40px; padding-bottom: 30px; border-bottom: 1px solid rgba(6, 20, 27, 0.05);">
            <div style="width: 100px; height: 100px; background: #253745; color: #CCD0CF; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 800;">
                {{ strtoupper(substr($user->username, 0, 1)) }}
            </div>
            <div>
                <h2 style="font-size: 2rem; font-weight: 800; color: #06141B; letter-spacing: -1px; margin-bottom: 5px;">{{ $user->name ?? $user->username }}</h2>
                <p style="color: #4A5568; font-weight: 600; font-size: 1.1rem;">{{ ucfirst($user->role) }} Member</p>
            </div>
        </div>

        @if(session('success'))
            <div style="background: #D1FAE5; color: #065F46; padding: 15px 20px; border-radius: 12px; margin-bottom: 30px; font-weight: 700; border: 1px solid rgba(6, 95, 70, 0.1);">
                ✓ {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('alumni.profile.update') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 25px; margin-bottom: 30px;">
                <div>
                    <label style="display: block; margin-bottom: 10px; font-weight: 800; color: #253745; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid rgba(6, 20, 27, 0.1); background: #F8F9F9; font-weight: 600; font-size: 1rem;" placeholder="Your Name">
                </div>
                <div>
                    <label style="display: block; margin-bottom: 10px; font-weight: 800; color: #253745; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Username</label>
                    <input type="text" value="{{ $user->username }}" disabled style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid rgba(6, 20, 27, 0.05); background: #f0f0f0; color: #718096; font-weight: 600; font-size: 1rem; cursor: not-allowed;">
                </div>
            </div>

            <div style="margin-bottom: 35px;">
                <label style="display: block; margin-bottom: 10px; font-weight: 800; color: #253745; font-size: 0.9rem; text-transform: uppercase; letter-spacing: 0.5px;">Professional Bio</label>
                <textarea name="bio" rows="5" style="width: 100%; padding: 14px 20px; border-radius: 12px; border: 1px solid rgba(6, 20, 27, 0.1); background: #F8F9F9; font-weight: 600; font-size: 1rem; font-family: inherit; resize: none;" placeholder="Share your professional background and how you can help students...">{{ old('bio', $user->bio) }}</textarea>
            </div>

            <div style="display: flex; gap: 15px; justify-content: flex-end;">
                <button type="submit" class="btn btn-primary" style="padding: 15px 35px; font-size: 1rem;">Save Profile Changes</button>
            </div>
        </form>
    </div>
</div>
@endsection
