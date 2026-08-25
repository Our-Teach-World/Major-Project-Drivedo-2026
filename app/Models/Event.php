<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name', 'description', 'event_type', 'event_date',
        'event_end_date', 'venue', 'department', 'created_by', 'status',
    ];

    protected $casts = [
        'event_date' => 'date',
        'event_end_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function getCertificateCountAttribute(): int
    {
        return $this->certificates()->count();
    }
}
