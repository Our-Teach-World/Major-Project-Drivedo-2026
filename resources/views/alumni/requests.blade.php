@extends('alumni.layouts.app')

@section('title', 'Mentorship Requests - EduShare')
@section('header_title', '📩 Mentorship Requests')

@section('content')
    <div class="card">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #06141B; margin-bottom: 30px; letter-spacing: -0.5px;">Requests from Students</h2>
        @if($requests->isEmpty())
            <p style="text-align: center; color: #4A5568; padding: 40px; background: #F8F9F9; border-radius: 16px;">You haven't received any mentorship requests yet.</p>
        @else
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: separate; border-spacing: 0; min-width: 600px;">
                    <thead>
                        <tr>
                            <th style="padding: 18px 20px; text-align: left; background: #F8F9F9; color: #253745; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; border-top-left-radius: 16px; border-bottom-left-radius: 16px;">Student</th>
                            <th style="padding: 18px 20px; text-align: left; background: #F8F9F9; color: #253745; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Message</th>
                            <th style="padding: 18px 20px; text-align: left; background: #F8F9F9; color: #253745; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Received</th>
                            <th style="padding: 18px 20px; text-align: left; background: #F8F9F9; color: #253745; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">Status</th>
                            <th style="padding: 18px 20px; text-align: left; background: #F8F9F9; color: #253745; font-weight: 800; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px; border-top-right-radius: 16px; border-bottom-right-radius: 16px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $request)
                            <tr>
                                <td style="padding: 20px; border-bottom: 1px solid rgba(6, 20, 27, 0.05);">
                                    <div style="font-weight: 800; color: #06141B;">{{ $request->student->username }}</div>
                                </td>
                                <td style="padding: 20px; border-bottom: 1px solid rgba(6, 20, 27, 0.05); color: #4A5568; font-size: 0.95rem;">
                                    {{ Str::limit($request->message, 50) }}
                                </td>
                                <td style="padding: 20px; border-bottom: 1px solid rgba(6, 20, 27, 0.05); color: #4A5568; font-size: 0.9rem;">
                                    {{ $request->created_at->diffForHumans() }}
                                </td>
                                <td style="padding: 20px; border-bottom: 1px solid rgba(6, 20, 27, 0.05);">
                                    <span style="padding: 6px 14px; border-radius: 20px; font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px;
                                        @if($request->status === 'pending') background: #FEF3C7; color: #92400E;
                                        @elseif($request->status === 'accepted') background: #D1FAE5; color: #065F46;
                                        @else background: #FEE2E2; color: #991B1B; @endif">
                                        {{ ucfirst($request->status) }}
                                    </span>
                                </td>
                                <td style="padding: 20px; border-bottom: 1px solid rgba(6, 20, 27, 0.05);">
                                    @if($request->status === 'pending')
                                        <div style="display: flex; gap: 10px;">
                                            <form action="{{ route('alumni.requests.accept', $request->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn" style="padding: 8px 16px; font-size: 0.85rem;">Accept</button>
                                            </form>
                                            <form action="{{ route('alumni.requests.decline', $request->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-danger" style="padding: 8px 16px; font-size: 0.85rem;">Decline</button>
                                            </form>
                                        </div>
                                    @else
                                        <span style="color: #CBD5E0; font-size: 0.85rem; font-weight: 700;">PROCESSED</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
