<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookConversation extends Model
{
    use HasFactory;

    protected $table = 'book_conversations';

    protected $fillable = [
        'book_id',
        'sender_id',
        'receiver_id',
    ];

    /**
     * Get the book associated with this conversation
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Get the sender user
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * Get the receiver user
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Get messages in this conversation
     */
    public function messages()
    {
        return $this->hasMany(BookMessage::class, 'conversation_id')->orderBy('created_at', 'desc');
    }
}
