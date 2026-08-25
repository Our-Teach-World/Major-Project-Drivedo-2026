
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sell a Book - Campus Book Exchange</title>
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #0f172a; margin: 0; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px 20px; }
        
        .form-card {
            background: white; border-radius: 16px; overflow: hidden;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05); padding: 40px;
        }
        
        h1 { margin: 0 0 30px 0; font-size: 2rem; font-weight: 800; border-bottom: 2px solid #f1f5f9; padding-bottom: 20px; }
        
        .form-group { margin-bottom: 25px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 25px; }
        
        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr; }
        }
        
        label { display: block; font-weight: 600; margin-bottom: 8px; color: #475569; font-size: 0.95rem; }
        
        input[type="text"], input[type="number"], select, textarea {
            width: 100%; padding: 12px 15px; border: 2px solid #e2e8f0; border-radius: 8px;
            font-size: 1rem; transition: all 0.2s; background: #f8fafc; font-family: inherit;
        }
        
        input:focus, select:focus, textarea:focus { outline: none; border-color: #4f46e5; background: white; box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1); }
        
        .file-upload { border: 2px dashed #cbd5e1; padding: 30px; text-align: center; border-radius: 8px; background: #f8fafc; cursor: pointer; transition: 0.2s; }
        .file-upload:hover { border-color: #4f46e5; background: #eef2ff; }
        
        .btn-submit { background: #4f46e5; color: white; padding: 14px 28px; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; font-size: 1.1rem; width: 100%; transition: 0.2s; }
        .btn-submit:hover { background: #4338ca; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3); }
        
        .invalid-feedback { color: #dc2626; font-size: 0.85rem; margin-top: 5px; }
        
        .back-link { display: inline-flex; align-items: center; gap: 5px; color: #4f46e5; text-decoration: none; font-weight: 600; margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <a href="{{ route('books.index') }}" class="back-link">← Back to Marketplace</a>
        
        <div class="form-card">
            <h1>List a Book for Sale</h1>
            
            <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="form-group">
                    <label for="title">Book Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g. Introduction to Algorithms">
                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="form-row">
                    <div>
                        <label for="author">Author(s) *</label>
                        <input type="text" id="author" name="author" value="{{ old('author') }}" required placeholder="e.g. Thomas H. Cormen">
                        @error('author')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="subject">Subject / Course *</label>
                        <input type="text" id="subject" name="subject" value="{{ old('subject') }}" required placeholder="e.g. Data Structures">
                        @error('subject')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div>
                        <label for="class">Class / Year *</label>
                        <input type="text" id="class" name="class" value="{{ old('class') }}" required placeholder="e.g. B.Tech 2nd Year">
                        @error('class')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="price">Price (₹) (Leave 0 for free) *</label>
                        <input type="number" id="price" name="price" value="{{ old('price', 0) }}" min="0" required>
                        @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="form-row">
                    <div>
                        <label for="condition">Condition *</label>
                        <select id="condition" name="condition" required>
                            <option value="">Select Condition</option>
                            <option value="Like New" {{ old('condition') == 'Like New' ? 'selected' : '' }}>Like New</option>
                            <option value="Good" {{ old('condition') == 'Good' ? 'selected' : '' }}>Good</option>
                            <option value="Fair" {{ old('condition') == 'Fair' ? 'selected' : '' }}>Fair</option>
                            <option value="Poor" {{ old('condition') == 'Poor' ? 'selected' : '' }}>Poor</option>
                        </select>
                        @error('condition')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div>
                        <label for="college">College / Branch (Optional)</label>
                        <input type="text" id="college" name="college" value="{{ old('college', Auth::user()->studentProfile->branch ?? '') }}" placeholder="e.g. CS Dept">
                        @error('college')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="description">Description & Notes *</label>
                    <textarea id="description" name="description" rows="4" required placeholder="Mention any highlighting, torn pages, or edition details...">{{ old('description') }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <div class="form-group">
                    <label for="photo">Book Photo *</label>
                    <input type="file" id="photo" name="photo" accept="image/*" required style="width:100%; padding:10px; border:2px dashed #cbd5e1; border-radius:8px;">
                    @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                
                <button type="submit" class="btn-submit">Publish Listing</button>
            </form>
        </div>
    </div>
</body>
</html>

