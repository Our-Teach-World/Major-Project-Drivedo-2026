<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'author',
        'subject',
        'class',
        'college',
        'price',
        'description',
        'condition',
        'photo',
        'photos',
        'status',
    ];

    /**
     * Get the user who listed this book
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get conversations for this book
     */
    public function conversations()
    {
        return $this->hasMany(BookConversation::class, 'book_id');
    }
}
