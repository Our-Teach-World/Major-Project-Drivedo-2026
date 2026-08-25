<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class MentorshipRequest extends Model
{
    protected $fillable = [
        'student_id',
        'alumni_id',
        'message',
        'status',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }

    public function session(): HasOne
    {
        return $this->hasOne(MentorshipSession::class, 'mentorship_request_id');
    }
}
