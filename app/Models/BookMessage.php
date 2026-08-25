<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookMessage extends Model
{
    use HasFactory;

    protected $table = 'book_messages';

    protected $fillable = [
        'conversation_id',
        'sender_id',
        'text',
        'image',
    ];

    /**
     * Get the conversation this message belongs to
     */
    public function conversation()
    {
        return $this->belongsTo(BookConversation::class, 'conversation_id');
    }

    /**
     * Get the sender of this message
     */
    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
