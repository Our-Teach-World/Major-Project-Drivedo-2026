<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionMessage extends Model
{
    protected $fillable = [
        'session_id',
        'sender_id',
        'sender_type',
        'message',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(MentorshipSession::class, 'session_id');
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
