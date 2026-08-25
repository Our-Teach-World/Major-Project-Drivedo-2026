
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Book Listings - Book Exchange</title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; }
        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }
        
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px; }
        .header h1 { margin: 0; font-size: 2rem; font-weight: 800; }
        
        .btn-primary { background: #4f46e5; color: white; padding: 10px 20px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; text-decoration: none; transition: 0.2s; }
        .btn-primary:hover { background: #4338ca; }
        
        .book-table { width: 100%; border-collapse: collapse; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.05); }
        .book-table th, .book-table td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #f1f5f9; }
        .book-table th { background: #f8fafc; font-weight: 600; color: #475569; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.5px; }
        
        .book-title-cell { display: flex; align-items: center; gap: 15px; }
        .book-thumb { width: 50px; height: 70px; object-fit: cover; border-radius: 4px; background: #e2e8f0; }
        .book-name { font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .book-price { color: #64748b; font-size: 0.9rem; }
        
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600; }
        .badge-available { background: #dcfce7; color: #166534; }
        .badge-sold { background: #f1f5f9; color: #475569; }
        .badge-removed { background: #fee2e2; color: #991b1b; }
        
        .action-btns { display: flex; gap: 10px; }
        .btn-small { padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 600; cursor: pointer; text-decoration: none; border: none; }
        .btn-status { background: #f1f5f9; color: #475569; transition: 0.2s; }
        .btn-status:hover { background: #e2e8f0; }
        .btn-danger { background: #fee2e2; color: #dc2626; transition: 0.2s; }
        .btn-danger:hover { background: #fca5a5; }

        .back-link { display: inline-flex; align-items: center; gap: 5px; color: #4f46e5; text-decoration: none; font-weight: 600; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('books.index') }}" class="back-link">← Back to Marketplace</a>
        
        <div class="header">
            <h1>My Book Listings</h1>
            <a href="{{ route('books.create') }}" class="btn-primary">➕ Sell New Book</a>
        </div>
        
        @if(session('success'))
            <div style="background: #dcfce7; color: #166534; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-weight: 600;">
                {{ session('success') }}
            </div>
        @endif
        
        @if($books->isEmpty())
            <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; border: 2px dashed #cbd5e1;">
                <div style="font-size: 3rem; margin-bottom: 15px;">📚</div>
                <h2 style="margin-bottom: 10px;">No books listed yet</h2>
                <p style="color: #64748b; margin-bottom: 20px;">You haven't listed any books for sale.</p>
                <a href="{{ route('books.create') }}" class="btn-primary">Create Your First Listing</a>
            </div>
        @else
            <table class="book-table">
                <thead>
                    <tr>
                        <th>Book Details</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Listed On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($books as $book)
                        <tr>
                            <td>
                                <div class="book-title-cell">
                                    @if($book->photo)
                                        <img src="{{ $book->photo }}" class="book-thumb" alt="Thumb">
                                    @else
                                        <div class="book-thumb"></div>
                                    @endif
                                    <div>
                                        <div class="book-name">{{ Str::limit($book->title, 40) }}</div>
                                        <div class="book-price">{{ $book->price > 0 ? '₹'.number_format($book->price, 0) : 'Free' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $book->subject }}</td>
                            <td>
                                @php
                                    $badgeClass = 'badge-available';
                                    if($book->status == 'Sold') $badgeClass = 'badge-sold';
                                    if($book->status == 'Removed') $badgeClass = 'badge-removed';
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $book->status }}</span>
                            </td>
                            <td style="color: #64748b; font-size: 0.9rem;">{{ $book->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="action-btns">
                                    <form action="{{ route('books.status', $book->id) }}" method="POST">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" style="padding: 4px; border-radius: 4px; border: 1px solid #cbd5e1;">
                                            <option value="Available" {{ $book->status == 'Available' ? 'selected' : '' }}>Available</option>
                                            <option value="Sold" {{ $book->status == 'Sold' ? 'selected' : '' }}>Sold</option>
                                            <option value="Removed" {{ $book->status == 'Removed' ? 'selected' : '' }}>Removed</option>
                                        </select>
                                    </form>
                                    <a href="{{ route('books.show', $book->id) }}" class="btn-small btn-status" style="text-decoration:none;">View</a>
                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?');">
                                        @csrf
                                        <button type="submit" class="btn-small btn-danger">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>
</html>

