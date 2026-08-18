
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $book->title }} - Book Exchange</title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; }
        .container { max-width: 1000px; margin: 0 auto; padding: 30px 20px; }
        
        .book-details {
            background: white; border-radius: 16px; overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            display: grid; grid-template-columns: 1fr 1.5fr;
            margin-bottom: 30px;
        }
        
        @media (max-width: 768px) {
            .book-details { grid-template-columns: 1fr; }
        }
        
        .book-image-container { background: #f1f5f9; display: flex; align-items: center; justify-content: center; }
        .book-image { width: 100%; height: 100%; object-fit: cover; max-height: 500px; }
        .book-placeholder { font-size: 5rem; color: #cbd5e1; padding: 100px 0; }
        
        .book-info { padding: 40px; }
        .price-tag { display: inline-block; background: #4f46e5; color: white; padding: 8px 16px; border-radius: 30px; font-weight: 800; font-size: 1.5rem; margin-bottom: 20px; }
        
        .title { font-size: 2.2rem; font-weight: 800; margin: 0 0 10px 0; line-height: 1.2; }
        .author { font-size: 1.2rem; color: #64748b; margin-bottom: 25px; }
        
        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 30px; background: #f8fafc; padding: 20px; border-radius: 12px; }
        .meta-item { display: flex; flex-direction: column; }
        .meta-label { font-size: 0.85rem; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; margin-bottom: 5px; }
        .meta-value { font-weight: 700; color: #1e293b; }
        
        .description-box { margin-bottom: 30px; }
        .description-box h3 { margin: 0 0 10px 0; font-size: 1.1rem; }
        .description-content { color: #475569; line-height: 1.7; }
        
        .action-box { padding-top: 30px; border-top: 1px solid #e2e8f0; display: flex; gap: 15px; align-items: center; }
        .seller-profile { display: flex; align-items: center; gap: 15px; flex: 1; }
        .seller-avatar { width: 50px; height: 50px; background: #cbd5e1; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 700; color: white; }
        .seller-name { font-weight: 700; font-size: 1.1rem; }
        .seller-role { font-size: 0.85rem; color: #64748b; }
        
        .btn-primary { background: #4f46e5; color: white; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; text-decoration: none; font-size: 1rem; transition: 0.2s; display: inline-flex; align-items: center; gap: 8px; }
        .btn-primary:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 4px 10px rgba(79, 70, 229, 0.3); }
        
        .btn-secondary { background: #f1f5f9; color: #475569; padding: 12px 24px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; }
        .btn-secondary:hover { background: #e2e8f0; }

        .back-link { display: inline-flex; align-items: center; gap: 5px; color: #4f46e5; text-decoration: none; font-weight: 600; margin-bottom: 20px; }
        .back-link:hover { text-decoration: underline; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('books.index') }}" class="back-link">← Back to Marketplace</a>
        
        <div class="book-details">
            <div class="book-image-container">
                @if($book->photo)
                    <img src="{{ $book->photo }}" alt="{{ $book->title }}" class="book-image">
                @else
                    <div class="book-placeholder">📚</div>
                @endif
            </div>
            
            <div class="book-info">
                <div class="price-tag">
                    @if($book->price > 0)
                        ₹{{ number_format($book->price, 0) }}
                    @else
                        Free
                    @endif
                </div>
                
                <h1 class="title">{{ $book->title }}</h1>
                <div class="author">By {{ $book->author }}</div>
                
                <div class="meta-grid">
                    <div class="meta-item">
                        <span class="meta-label">Subject / Course</span>
                        <span class="meta-value">{{ $book->subject }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Class / Year</span>
                        <span class="meta-value">{{ $book->class }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Condition</span>
                        <span class="meta-value">{{ $book->condition }}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Status</span>
                        <span class="meta-value" style="color: {{ $book->status == 'Available' ? '#166534' : '#991b1b' }};">{{ $book->status }}</span>
                    </div>
                </div>
                
                <div class="description-box">
                    <h3>Description</h3>
                    <div class="description-content">
                        {!! nl2br(e($book->description)) !!}
                    </div>
                </div>
                
                <div class="action-box">
                    <div class="seller-profile">
                        <div class="seller-avatar">
                            {{ strtoupper(substr($book->user->name ?? $book->user->username ?? 'U', 0, 1)) }}
                        </div>
                        <div>
                            <div class="seller-name">{{ $book->user->name ?? $book->user->username ?? 'Unknown Student' }}</div>
                            <div class="seller-role">Listed on {{ $book->created_at->format('M d, Y') }}</div>
                        </div>
                    </div>
                    
                    @if(Auth::id() !== $book->user_id && $book->status == 'Available')
                        <a href="{{ route('books.chat.start', $book->id) }}" class="btn-primary">
                            💬 Message Seller
                        </a>
                    @elseif(Auth::id() === $book->user_id)
                        <a href="{{ route('books.my-listings') }}" class="btn-secondary">
                            Manage Listing
                        </a>
                    @else
                        <button disabled class="btn-secondary" style="opacity: 0.6; cursor: not-allowed;">
                            Not Available
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</body>
</html>

