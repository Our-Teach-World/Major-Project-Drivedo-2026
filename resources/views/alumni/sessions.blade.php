@extends('alumni.layouts.app')

@section('title', 'My Sessions - EduShare')
@section('header_title', '📅 My Mentorship Sessions')

@section('content')
    <div class="card">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #06141B; margin-bottom: 30px; letter-spacing: -0.5px;">Scheduled Sessions</h2>
        @if($sessions->isEmpty())
            <p style="text-align: center; color: #4A5568; padding: 40px; background: #F8F9F9; border-radius: 16px;">No sessions scheduled yet.</p>
        @else
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 30px;">
                @foreach($sessions as $session)
                    <div class="card" style="margin-bottom: 0; padding: 30px; background: #F8F9F9; border-color: rgba(6, 20, 27, 0.03);">
                        <div style="margin-bottom: 20px;">
                            <span style="padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
                                @if($session->status === 'scheduled') background: #DBEAFE; color: #1E40AF;
                                @elseif($session->status === 'completed') background: #D1FAE5; color: #065F46;
                                @else background: #FEE2E2; color: #991B1B; @endif">
                                {{ ucfirst($session->status) }}
                            </span>
                        </div>
                        <h3 style="font-size: 1.3rem; font-weight: 800; color: #06141B; margin-bottom: 12px; letter-spacing: -0.3px;">{{ $session->title }}</h3>
                        <p style="margin-bottom: 8px; color: #4A5568; font-size: 0.95rem;">Student: <strong style="color: #253745;">{{ $session->student->username }}</strong></p>
                        <p style="margin-bottom: 25px; color: #718096; font-size: 0.9rem;">
                            <span style="margin-right: 5px;">📅</span> {{ $session->scheduled_at->format('M d, Y') }}
                            <span style="margin: 0 10px; color: #CBD5E0;">|</span>
                            <span style="margin-right: 5px;">⏰</span> {{ $session->scheduled_at->format('h:i A') }}
                        </p>
                        
                        <a href="{{ route('alumni.session.chat', $session->id) }}" class="btn" style="width: 100%;">Join Session Chat</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
