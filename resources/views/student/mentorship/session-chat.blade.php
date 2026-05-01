<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentorship Chat - EduShare</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #CCD0CF; padding: 40px 20px; color: #06141B; }
        .container { max-width: 900px; margin: 0 auto; }
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
        .chat-header h2 { font-size: 1.4rem; font-weight: 800; letter-spacing: -0.5px; }
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
            font-size: 1rem; 
            line-height: 1.5; 
            position: relative;
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
            box-shadow: 0 4px 15px rgba(6, 20, 27, 0.04);
        }
        .chat-input-area { 
            padding: 30px 35px; 
            border-top: 1px solid rgba(6, 20, 27, 0.08); 
            background: #ffffff; 
        }
        .chat-form { display: flex; gap: 15px; }
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
        .chat-input:focus { outline: none; border-color: #253745; box-shadow: 0 0 0 4px rgba(37, 55, 69, 0.1); }
        .btn { 
            padding: 12px 30px; 
            background: #253745; 
            color: #CCD0CF; 
            border: none; 
            border-radius: 12px; 
            font-weight: 800; 
            cursor: pointer; 
            transition: all 0.3s;
        }
        .btn:hover { background: #1a2833; transform: translateY(-1px); }
    </style>
</head>
<body>
    <div class="container">
        <div class="chat-container">
            <div class="chat-header">
                <div>
                    <h2 style="font-size: 1.2rem;">{{ $session->title }}</h2>
                    <p style="font-size: 0.8rem; opacity: 0.8;">With Alumni: <strong>{{ $session->alumni->username }}</strong></p>
                </div>
                <a href="{{ route('mentorship.sessions') }}" style="color: #fff; text-decoration: none; font-size: 0.9rem; font-weight: 700;">Back</a>
            </div>

            <div class="chat-messages" id="chat-messages">
                @foreach($session->messages as $message)
                    <div class="message {{ $message->sender_id === Auth::id() ? 'sent' : 'received' }}">
                        <div class="message-content">{{ $message->message }}</div>
                        <div style="font-size: 0.7rem; margin-top: 5px; opacity: 0.6;">{{ $message->created_at->format('h:i A') }}</div>
                    </div>
                @endforeach
            </div>

            <div class="chat-input-area">
                <form action="{{ route('mentorship.session.message', $session->id) }}" method="POST" class="chat-form">
                    @csrf
                    <input type="text" name="message" class="chat-input" placeholder="Type your message here..." required autocomplete="off">
                    <button type="submit" class="btn">Send</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const chatMessages = document.getElementById('chat-messages');
        chatMessages.scrollTop = chatMessages.scrollHeight;
    </script>
</body>
</html>
