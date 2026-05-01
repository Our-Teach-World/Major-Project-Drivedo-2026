<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Campus Book Exchange - EduShare</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: #CCD0CF; color: #06141B; line-height: 1.6; }
        
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }

        .marketplace-header {
            background: #ffffff;
            color: #06141B;
            padding: 50px 30px;
            text-align: center;
            border-radius: 32px;
            margin-bottom: 40px;
            border: 1px solid rgba(6, 20, 27, 0.05);
            box-shadow: 0 10px 40px rgba(6, 20, 27, 0.05);
        }
        .marketplace-header h1 { margin: 0 0 10px 0; font-size: 2.8rem; font-weight: 800; letter-spacing: -1.5px; color: #253745; }
        .marketplace-header p { margin: 0; font-size: 1.2rem; color: #4A5568; font-weight: 500; }
        
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: #ffffff;
            padding: 20px 30px;
            border-radius: 20px;
            border: 1px solid rgba(6, 20, 27, 0.05);
            box-shadow: 0 4px 15px rgba(6, 20, 27, 0.03);
            gap: 20px;
            flex-wrap: wrap;
        }
        
        .search-form { display: flex; gap: 12px; flex: 1; min-width: 300px; }
        .search-input {
            flex: 1; padding: 12px 20px; border: 1px solid rgba(6, 20, 27, 0.1);
            border-radius: 12px; font-size: 1rem; background: #F8F9F9;
            color: #06141B; font-weight: 500; transition: all 0.2s;
        }
        .search-input:focus { outline: none; border-color: #253745; background: #fff; box-shadow: 0 0 0 3px rgba(37, 55, 69, 0.1); }
        
        .btn {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
        }
        .btn-primary { background: #253745; color: #CCD0CF; box-shadow: 0 4px 12px rgba(37, 55, 69, 0.15); }
        .btn-primary:hover { background: #1a2833; transform: translateY(-2px); box-shadow: 0 8px 20px rgba(37, 55, 69, 0.2); }
        
        .btn-outline {
            background: #ffffff; color: #253745; border: 1px solid rgba(37, 55, 69, 0.1);
        }
        .btn-outline:hover { background: #F2F4F3; border-color: #253745; transform: translateY(-2px); }
        
        .nav-actions { display: flex; gap: 12px; }

        .book-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 30px;
        }
        .book-card {
            background: #ffffff; border-radius: 24px; overflow: hidden;
            border: 1px solid rgba(6, 20, 27, 0.05);
            box-shadow: 0 4px 20px rgba(6, 20, 27, 0.04);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; display: flex; flex-direction: column;
        }
        .book-card:hover { transform: translateY(-8px); box-shadow: 0 15px 35px rgba(6, 20, 27, 0.08); border-color: rgba(37, 55, 69, 0.1); }
        
        .book-image { width: 100%; height: 220px; object-fit: cover; }
        .book-placeholder { width: 100%; height: 220px; background: #F2F4F3; display: flex; align-items: center; justify-content: center; font-size: 4rem; color: #A0AEC0; }
        
        .book-content { padding: 25px; flex: 1; display: flex; flex-direction: column; }
        .book-price { 
            position: absolute; top: 20px; right: 20px; 
            background: #253745; color: #CCD0CF; 
            padding: 8px 16px; border-radius: 12px; 
            font-weight: 800; font-size: 1.1rem; 
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .book-title { font-size: 1.4rem; font-weight: 800; margin-bottom: 6px; color: #06141B; letter-spacing: -0.5px; }
        .book-author { font-size: 0.95rem; color: #4A5568; font-weight: 600; margin-bottom: 20px; }
        
        .book-meta { 
            display: flex; justify-content: space-between; font-size: 0.85rem; 
            color: #4A5568; margin-bottom: 20px; background: #F8F9F9; 
            padding: 15px; border-radius: 16px; border: 1px solid rgba(6, 20, 27, 0.03);
        }
        .condition-badge { display: inline-block; padding: 4px 12px; border-radius: 8px; font-weight: 800; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px; }
        .cond-likenew { background: #D1FAE5; color: #065F46; }
        .cond-good { background: #FEF3C7; color: #92400E; }
        .cond-fair { background: #FFEDD5; color: #9A3412; }
        .cond-poor { background: #FEE2E2; color: #991B1B; }
        
        .book-footer { margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(6, 20, 27, 0.05); display: flex; justify-content: space-between; align-items: center; }
        .seller-info { display: flex; align-items: center; gap: 10px; font-size: 0.9rem; font-weight: 700; color: #253745; }
        
        .pagination { margin-top: 50px; display: flex; justify-content: center; }
        
        .alert { padding: 20px; border-radius: 16px; margin-bottom: 30px; font-weight: 700; display: flex; align-items: center; gap: 12px; }
        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid rgba(6, 95, 70, 0.1); }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid rgba(153, 27, 27, 0.1); }
    </style>
</head>
<body>
    <div class="container">
        <!-- Back button -->
        <div style="margin-bottom: 25px;">
            <a href="{{ route('student.dashboard') }}" class="btn btn-outline" style="padding: 8px 16px;">← Back to Dashboard</a>
        </div>

        <div class="marketplace-header">
            <h1>Campus Book Exchange</h1>
            <p>Sustainable learning through peer-to-peer textbook trading.</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <span>✓</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <span>✕</span> {{ session('error') }}
            </div>
        @endif

        <div class="toolbar">
            <form action="{{ route('books.index') }}" method="GET" class="search-form">
                <input type="text" name="search" class="search-input" placeholder="Search by title, author, or subject..." value="{{ request('search') }}">
                <select name="condition" class="search-input" style="max-width: 150px;">
                    <option value="">Any Condition</option>
                    <option value="Like New" {{ request('condition') == 'Like New' ? 'selected' : '' }}>Like New</option>
                    <option value="Good" {{ request('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                    <option value="Fair" {{ request('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                    <option value="Poor" {{ request('condition') == 'Poor' ? 'selected' : '' }}>Poor</option>
                </select>
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
            
            <div class="nav-actions">
                <a href="{{ route('books.chat.index') }}" class="btn btn-outline">💬 Messages</a>
                <a href="{{ route('books.my-listings') }}" class="btn btn-outline">📚 My Listings</a>
                <a href="{{ route('books.create') }}" class="btn btn-primary">➕ Sell a Book</a>
            </div>
        </div>

        <div class="book-grid">
            @forelse($books as $book)
                <div class="book-card">
                    <div class="book-price">
                        @if($book->price > 0)
                            ₹{{ number_format($book->price, 0) }}
                        @else
                            Free
                        @endif
                    </div>
                    
                    @if($book->photo)
                        <img src="{{ $book->photo }}" alt="{{ $book->title }}" class="book-image">
                    @else
                        <div class="book-placeholder">📚</div>
                    @endif
                    
                    <div class="book-content">
                        <h3 class="book-title">{{ Str::limit($book->title, 40) }}</h3>
                        <div class="book-author">By {{ Str::limit($book->author, 30) }}</div>
                        
                        <div class="book-meta">
                            <div>
                                <div style="font-weight: 800; color: #06141B; margin-bottom: 2px;">{{ $book->subject }}</div>
                                <div style="font-weight: 600;">{{ $book->class }}</div>
                            </div>
                            <div style="text-align: right;">
                                @php
                                    $condClass = 'cond-good';
                                    if($book->condition == 'Like New') $condClass = 'cond-likenew';
                                    if($book->condition == 'Fair') $condClass = 'cond-fair';
                                    if($book->condition == 'Poor') $condClass = 'cond-poor';
                                @endphp
                                <span class="condition-badge {{ $condClass }}">{{ $book->condition }}</span>
                            </div>
                        </div>
                        
                        <div class="book-footer">
                            <div class="seller-info">
                                <span style="background: #F2F4F3; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 50%; border: 1px solid rgba(6, 20, 27, 0.05);">👤</span>
                                {{ $book->user->name ?? $book->user->username ?? 'Unknown' }}
                            </div>
                            <a href="{{ route('books.show', $book->id) }}" class="btn btn-primary" style="padding: 8px 18px; font-size: 0.9rem;">View Details</a>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 100px 30px; background: #ffffff; border-radius: 32px; border: 1px solid rgba(6, 20, 27, 0.05); box-shadow: 0 4px 20px rgba(6, 20, 27, 0.03);">
                    <div style="font-size: 4rem; margin-bottom: 20px;">🔍</div>
                    <h2 style="margin-bottom: 10px; font-weight: 800;">No books found</h2>
                    <p style="color: #4A5568; margin-bottom: 30px; font-size: 1.1rem;">We couldn't find any books matching your criteria.</p>
                    <a href="{{ route('books.index') }}" class="btn btn-outline">Clear All Filters</a>
                </div>
            @endforelse
        </div>
        
        <div class="pagination">
            {{ $books->links('pagination::bootstrap-4') }}
        </div>
    </div>
</body>
</html>
