@extends('alumni.layouts.app')

@section('title', 'Session Chat - CampusCore')
@section('header_title', '💬 Mentorship Chat')

@push('styles')
    <style>
        .chat-container {
            display: flex;
            flex-direction: column;
            height: 700px;
            background: #ffffff;
            border: 1px solid rgba(6, 20, 27, 0.05);
            border-radius: 32px;
            box-shadow: 0 20px 50px rgba(6, 20, 27, 0.1);
            overflow: hidden;
        }

        .chat-header {
            background: #253745;
            color: #CCD0CF;
            padding: 25px 35px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 35px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            background: #F8F9F9;
        }

        .message {
            max-width: 70%;
            padding: 16px 24px;
            border-radius: 20px;
            position: relative;
            font-size: 1rem;
            line-height: 1.5;
            box-shadow: 0 4px 15px rgba(6, 20, 27, 0.04);
        }

        .message.sent {
            align-self: flex-end;
            background: #253745;
            color: #CCD0CF;
            border-bottom-right-radius: 4px;
            box-shadow: 0 4px 15px rgba(37, 55, 69, 0.2);
        }

        .message.received {
            align-self: flex-start;
            background: #ffffff;
            color: #06141B;
            border: 1px solid rgba(6, 20, 27, 0.08);
            border-bottom-left-radius: 4px;
        }

        .message-info {
            font-size: 0.75rem;
            margin-top: 8px;
            opacity: 0.6;
            font-weight: 600;
        }

        .chat-input-area {
            padding: 30px 35px;
            border-top: 1px solid rgba(6, 20, 27, 0.08);
            background: #ffffff;
        }

        .chat-form {
            display: flex;
            gap: 15px;
        }

        .chat-input {
            flex: 1;
            padding: 16px 24px;
            border: 1px solid rgba(6, 20, 27, 0.1);
            border-radius: 16px;
            font-size: 1rem;
            background: #F8F9F9;
            color: #06141B;
            transition: all 0.2s;
        }
        
        .chat-input:focus {
            outline: none;
            border-color: #253745;
            box-shadow: 0 0 0 4px rgba(37, 55, 69, 0.1);
        }
    </style>
@endpush

@section('content')
    <div class="chat-container">
        <div class="chat-header">
            <div>
                <h3 style="margin: 0;">{{ $session->title }}</h3>
                <span style="font-size: 0.8rem; opacity: 0.8;">Chatting with <strong>{{ $session->student->username }}</strong></span>
            </div>
            <a href="{{ route('alumni.sessions') }}" style="color: #fff; text-decoration: none; font-weight: 600;">Back to Sessions</a>
        </div>

        <div class="chat-messages" id="chat-messages">
            @forelse($session->messages as $message)
                <div class="message {{ $message->sender_id === Auth::id() ? 'sent' : 'received' }}">
                    <div class="message-content">{{ $message->message }}</div>
                    <div class="message-info">
                        {{ $message->created_at->format('h:i A') }}
                    </div>
                </div>
            @empty
                <div style="text-align: center; color: #666; margin-top: 50px;">
                    No messages yet. Start the conversation!
                </div>
            @endforelse
        </div>

        <div class="chat-input-area">
            <form action="{{ route('alumni.session.message', $session->id) }}" method="POST" class="chat-form">
                @csrf
                <input type="text" name="message" class="chat-input" placeholder="Type your message here..." required autocomplete="off">
                <button type="submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Scroll to bottom of chat
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    </script>
@endpush
