<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookExchangeController extends Controller
{
    /**
     * Display the main book exchange index.
     */
    public function index(Request $request)
    {
        $query = Book::where('status', 'Available');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('author', 'like', "%{$search}%")
                  ->orWhere('subject', 'like', "%{$search}%");
            });
        }

        if ($request->has('condition') && !empty($request->condition)) {
            $query->where('condition', $request->condition);
        }

        $books = $query->with('user')->orderBy('created_at', 'desc')->paginate(12);

        return view('marketplace.books.index', compact('books'));
    }

    /**
     * Show form to list a new book.
     */
    public function create()
    {
        return view('marketplace.books.create');
    }

    /**
     * Store a new book listing.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'college' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'description' => 'required|string',
            'condition' => 'required|string|in:Like New,Good,Fair,Poor',
            'photo' => 'required|image|max:5120', // Accept real file upload max 5MB
        ]);

        $book = new Book();
        $book->user_id = Auth::id();
        $book->title = $request->title;
        $book->author = $request->author;
        $book->subject = $request->subject;
        $book->class = $request->class;
        $book->college = $request->college ?? Auth::user()->branch ?? 'Campus';
        $book->price = $request->price ?? 0;
        $book->description = $request->description;
        $book->condition = $request->condition;
        $book->status = 'Available';

        if ($request->hasFile('photo')) {
            $file = $request->file('photo');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/books'), $filename);
            $book->photo = asset('uploads/books/' . $filename);
        }

        $book->save();

        return redirect()->route('books.my-listings')->with('success', 'Book listed successfully!');
    }

    /**
     * Display a specific book.
     */
    public function show($id)
    {
        $book = Book::with('user')->findOrFail($id);
        return view('marketplace.books.show', compact('book'));
    }

    /**
     * Display user's own listings.
     */
    public function myListings()
    {
        $books = Book::where('user_id', Auth::id())->orderBy('created_at', 'desc')->get();
        return view('marketplace.books.my-listings', compact('books'));
    }

    /**
     * Mark a book as sold or available.
     */
    public function updateStatus(Request $request, $id)
    {
        $book = Book::findOrFail($id);
        
        if ($book->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $book->status = $request->status; // 'Available', 'Sold', 'Removed'
        $book->save();

        return back()->with('success', 'Status updated successfully!');
    }

    /**
     * Delete a book listing.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        
        if ($book->user_id !== Auth::id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        $book->delete();

        return redirect()->route('books.my-listings')->with('success', 'Listing deleted.');
    }
}
