<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookConversation;
use App\Models\BookMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookExchangeChatController extends Controller
{
    /**
     * Display a list of all conversations for the user
     */
    public function index()
    {
        $user = Auth::user();

        $conversations = BookConversation::with(['book', 'sender', 'receiver', 'messages' => function($query) {
                $query->orderBy('created_at', 'desc');
            }])
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->get()
            ->sortByDesc(function($conversation) {
                $lastMsg = $conversation->messages->first();
                return $lastMsg ? $lastMsg->created_at : $conversation->updated_at;
            });

        return view('marketplace.chat.index', compact('conversations'));
    }

    /**
     * Start a conversation about a specific book, or load existing one
     */
    public function getByBook($bookId)
    {
        $user = Auth::user();
        $book = Book::with('user')->findOrFail($bookId);

        // Cannot chat with yourself
        if ($user->id === $book->user_id) {
            return redirect()->route('books.my-listings')->with('error', 'You cannot message yourself about your own book.');
        }

        $senderId = min($user->id, $book->user_id);
        $receiverId = max($user->id, $book->user_id);

        $conversation = BookConversation::where('book_id', $book->id)
            ->where(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)
                      ->where('receiver_id', $receiverId);
            })
            ->first();

        if (!$conversation) {
            $conversation = BookConversation::create([
                'book_id' => $book->id,
                'sender_id' => $senderId,
                'receiver_id' => $receiverId,
            ]);
        }

        return redirect()->route('books.chat.show', $conversation->id);
    }

    /**
     * Show a specific conversation
     */
    public function show($id)
    {
        $user = Auth::user();
        $conversation = BookConversation::with(['book.user', 'messages.sender', 'sender', 'receiver'])->findOrFail($id);

        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            abort(403, 'Unauthorized access to this conversation.');
        }

        return view('marketplace.chat.show', compact('conversation'));
    }

    /**
     * Send a message in a conversation
     */
    public function sendMessage(Request $request, $id)
    {
        $user = Auth::user();
        $conversation = BookConversation::findOrFail($id);

        if ($conversation->sender_id !== $user->id && $conversation->receiver_id !== $user->id) {
            abort(403, 'Unauthorized access.');
        }

        $request->validate([
            'text' => 'required_without:image|string|nullable',
            'image' => 'nullable|image|max:2048',
        ]);

        $messageData = [
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'text' => $request->text ?? '',
        ];

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/books/chat'), $filename);
            $messageData['image'] = asset('uploads/books/chat/' . $filename);
        }

        BookMessage::create($messageData);

        return back()->with('success', 'Message sent.');
    }
}
