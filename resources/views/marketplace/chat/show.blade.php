
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat - Book Exchange</title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; }
        .container { max-width: 900px; margin: 0 auto; padding: 20px; height: calc(100vh - 100px); display: flex; flex-direction: column; }
        
        .chat-box { background: white; border-radius: 16px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); display: flex; flex-direction: column; flex: 1; }
        
        .chat-header { background: white; padding: 20px; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between; }
        .header-user { display: flex; align-items: center; gap: 15px; }
        .user-avatar { width: 45px; height: 45px; background: #4f46e5; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 1.2rem; }
        .user-name { font-weight: 700; font-size: 1.1rem; margin-bottom: 2px; }
        .book-ref { font-size: 0.85rem; color: #64748b; }
        .book-ref a { color: #4f46e5; text-decoration: none; }
        .book-ref a:hover { text-decoration: underline; }
        
        .messages-area { flex: 1; padding: 20px; overflow-y: auto; background: #f8fafc; display: flex; flex-direction: column; gap: 15px; }
        
        .msg { max-width: 70%; padding: 12px 16px; border-radius: 12px; position: relative; }
        .msg-text { line-height: 1.5; }
        .msg-time { font-size: 0.7rem; opacity: 0.7; margin-top: 5px; text-align: right; }
        
        .msg-in { background: white; border: 1px solid #e2e8f0; align-self: flex-start; border-bottom-left-radius: 4px; }
        .msg-out { background: #4f46e5; color: white; align-self: flex-end; border-bottom-right-radius: 4px; }
        
        .msg-img { max-width: 100%; border-radius: 8px; margin-top: 5px; }
        
        .chat-input-area { padding: 20px; background: white; border-top: 1px solid #f1f5f9; }
        .chat-form { display: flex; gap: 10px; align-items: flex-end; }
        
        .input-wrapper { flex: 1; display: flex; flex-direction: column; gap: 10px; }
        .text-input { width: 100%; border: 2px solid #e2e8f0; border-radius: 8px; padding: 12px; font-family: inherit; font-size: 1rem; resize: none; transition: 0.2s; }
        .text-input:focus { outline: none; border-color: #4f46e5; }
        
        .file-input { font-size: 0.85rem; }
        
        .btn-send { background: #4f46e5; color: white; border: none; width: 50px; height: 50px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: 0.2s; font-size: 1.2rem; }
        .btn-send:hover { background: #4338ca; transform: scale(1.05); }

        .back-link { display: inline-flex; align-items: center; gap: 5px; color: #4f46e5; text-decoration: none; font-weight: 600; margin-bottom: 10px; }
    </style>
</head>
<body>
    @php
        $otherUser = $conversation->sender_id === Auth::id() ? $conversation->receiver : $conversation->sender;
    @endphp
    <div class="container">
        <a href="{{ route('books.chat.index') }}" class="back-link">← Back to Messages</a>
        
        <div class="chat-box">
            <div class="chat-header">
                <div class="header-user">
                    <div class="user-avatar">{{ strtoupper(substr($otherUser->name ?? $otherUser->username ?? 'U', 0, 1)) }}</div>
                    <div>
                        <div class="user-name">{{ $otherUser->name ?? $otherUser->username ?? 'Unknown' }}</div>
                        <div class="book-ref">Discussing: <a href="{{ route('books.show', $conversation->book->id) }}">{{ $conversation->book->title }}</a></div>
                    </div>
                </div>
                <div>
                    @if($conversation->book->price > 0)
                        <span style="background: #eef2ff; color: #4f46e5; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.9rem;">₹{{ number_format($conversation->book->price, 0) }}</span>
                    @else
                        <span style="background: #eef2ff; color: #4f46e5; padding: 6px 12px; border-radius: 20px; font-weight: 700; font-size: 0.9rem;">Free</span>
                    @endif
                </div>
            </div>
            
            <div class="messages-area" id="messagesArea">
                <div style="text-align: center; margin-bottom: 20px; font-size: 0.85rem; color: #94a3b8;">
                    This is the start of your conversation regarding "{{ $conversation->book->title }}"
                </div>
                
                @foreach($conversation->messages->reverse() as $msg)
                    <div class="msg {{ $msg->sender_id === Auth::id() ? 'msg-out' : 'msg-in' }}">
                        @if($msg->text)
                            <div class="msg-text">{!! nl2br(e($msg->text)) !!}</div>
                        @endif
                        @if($msg->image)
                            <img src="{{ $msg->image }}" class="msg-img" alt="Attachment">
                        @endif
                        <div class="msg-time">{{ $msg->created_at->format('h:i A') }}</div>
                    </div>
                @endforeach
            </div>
            
            <div class="chat-input-area">
                <form action="{{ route('books.chat.message', $conversation->id) }}" method="POST" enctype="multipart/form-data" class="chat-form">
                    @csrf
                    <div class="input-wrapper">
                        <textarea name="text" class="text-input" rows="1" placeholder="Type a message..." style="min-height: 48px;"></textarea>
                        <input type="file" name="image" accept="image/*" class="file-input">
                    </div>
                    <button type="submit" class="btn-send" title="Send">➤</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        // Auto scroll to bottom
        const messagesArea = document.getElementById('messagesArea');
        messagesArea.scrollTop = messagesArea.scrollHeight;
    </script>
</body>
</html>

