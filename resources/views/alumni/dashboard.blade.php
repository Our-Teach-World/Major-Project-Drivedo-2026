@extends('alumni.layouts.app')

@section('title', 'Alumni Dashboard - EduShare')
@section('header_title', '📊 Dashboard Overview')

@push('styles')
    <style>
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .stat-card {
            background-color: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            padding: 35px;
            border-radius: 24px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(6, 20, 27, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .stat-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(6, 20, 27, 0.1);
            border-color: rgba(37, 55, 69, 0.1);
        }

        .stat-number {
            font-size: 3rem;
            font-weight: 800;
            color: #06141B;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }

        .stat-label {
            font-weight: 700;
            color: #4A5568;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .upcoming-sessions h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 25px;
            color: #06141B;
            letter-spacing: -0.5px;
        }

        .session-card {
            background: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            border-radius: 20px;
            padding: 25px 30px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 15px rgba(6, 20, 27, 0.03);
            transition: all 0.3s ease;
        }

        .session-card:hover {
            transform: scale(1.01);
            box-shadow: 0 8px 25px rgba(6, 20, 27, 0.06);
        }

        .session-info h4 {
            font-size: 1.2rem;
            font-weight: 800;
            color: #06141B;
            margin-bottom: 8px;
        }

        .session-info p {
            font-size: 0.95rem;
            color: #4A5568;
            margin-bottom: 4px;
        }

        .session-info strong {
            color: #253745;
        }
    </style>
@endpush

@section('content')
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">{{ $pendingRequests }}</div>
            <div class="stat-label">Pending Requests</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">{{ $upcomingSessions->count() }}</div>
            <div class="stat-label">Upcoming Sessions</div>
        </div>
    </div>

    <div class="upcoming-sessions">
        <h2>📅 Upcoming Sessions</h2>
        @if($upcomingSessions->isEmpty())
            <p style="margin-top: 15px; color: #666;">No upcoming sessions scheduled.</p>
        @else
            <div style="margin-top: 20px;">
                @foreach($upcomingSessions as $session)
                    <div class="session-card">
                        <div class="session-info">
                            <h4>{{ $session->title }}</h4>
                            <p>With Student: <strong>{{ $session->student->username }}</strong></p>
                            <p>Date: {{ $session->scheduled_at->format('M d, Y - h:i A') }}</p>
                        </div>
                        <a href="{{ route('alumni.session.chat', $session->id) }}" class="btn btn-primary">Join Chat</a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
