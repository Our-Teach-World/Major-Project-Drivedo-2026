
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages - Book Exchange</title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; }
        .container { max-width: 1000px; margin: 0 auto; padding: 30px 20px; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 2rem; font-weight: 800; }
        
        .chat-list { display: flex; flex-direction: column; gap: 15px; }
        
        .chat-card {
            background: white; border-radius: 12px; padding: 20px; display: flex; align-items: center; gap: 20px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05); text-decoration: none; color: inherit; transition: 0.2s;
            border: 2px solid transparent;
        }
        .chat-card:hover { transform: translateY(-3px); border-color: #e2e8f0; box-shadow: 0 10px 15px rgba(0,0,0,0.08); }
        
        .book-thumb { width: 60px; height: 80px; object-fit: cover; border-radius: 6px; background: #e2e8f0; flex-shrink: 0; }
        
        .chat-info { flex: 1; }
        .chat-header { display: flex; justify-content: space-between; margin-bottom: 8px; }
        .user-name { font-weight: 700; font-size: 1.1rem; color: #1e293b; }
        .time { font-size: 0.85rem; color: #94a3b8; }
        
        .book-title { font-size: 0.9rem; color: #4f46e5; font-weight: 600; margin-bottom: 5px; }
        .last-message { color: #64748b; font-size: 0.95rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 500px; }
        
        .back-link { display: inline-flex; align-items: center; gap: 5px; color: #4f46e5; text-decoration: none; font-weight: 600; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('books.index') }}" class="back-link">← Back to Marketplace</a>
        
        <div class="header">
            <h1>My Messages</h1>
        </div>
        
        @if($conversations->isEmpty())
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; border: 2px dashed #cbd5e1;">
                <div style="font-size: 3rem; margin-bottom: 15px;">💬</div>
                <h2 style="margin-bottom: 10px;">No messages yet</h2>
                <p style="color: #64748b; margin-bottom: 20px;">When you contact sellers or buyers contact you, conversations will appear here.</p>
                <a href="{{ route('books.index') }}" style="background: #4f46e5; color: white; padding: 10px 20px; border-radius: 8px; text-decoration: none; font-weight: 600;">Browse Books</a>
            </div>
        @else
            <div class="chat-list">
                @foreach($conversations as $conv)
                    @php
                        $otherUser = $conv->sender_id === Auth::id() ? $conv->receiver : $conv->sender;
                        $lastMsg = $conv->messages->first();
                    @endphp
                    <a href="{{ route('books.chat.show', $conv->id) }}" class="chat-card">
                        @if($conv->book->photo)
                            <img src="{{ $conv->book->photo }}" class="book-thumb" alt="Book">
                        @else
                            <div class="book-thumb"></div>
                        @endif
                        
                        <div class="chat-info">
                            <div class="chat-header">
                                <span class="user-name">{{ $otherUser->name ?? $otherUser->username ?? 'Unknown' }}</span>
                                <span class="time">{{ $lastMsg ? $lastMsg->created_at->diffForHumans() : $conv->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="book-title">Re: {{ Str::limit($conv->book->title, 40) }}</div>
                            <div class="last-message">
                                @if($lastMsg)
                                    @if($lastMsg->sender_id === Auth::id()) <strong>You:</strong> @endif
                                    {{ $lastMsg->text ? Str::limit($lastMsg->text, 60) : '📷 Image' }}
                                @else
                                    <em>Start a conversation</em>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>

