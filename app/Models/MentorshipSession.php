<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MentorshipSession extends Model
{
    protected $table = 'mentorship_sessions';

    protected $fillable = [
        'student_id',
        'alumni_id',
        'mentorship_request_id',
        'title',
        'scheduled_at',
        'status',
        'notes',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function alumni(): BelongsTo
    {
        return $this->belongsTo(User::class, 'alumni_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SessionMessage::class, 'session_id')->orderBy('created_at');
    }

    public function mentorshipRequest(): BelongsTo
    {
        return $this->belongsTo(MentorshipRequest::class, 'mentorship_request_id');
    }
}
