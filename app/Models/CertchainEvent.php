<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertchainEvent extends Model
{
    protected $table = 'certchain_events';

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
        // Could be User or Admin depending on who created it.
        // If it's a teacher, it's User. If Admin, it's Admin.
        return $this->belongsTo(User::class, 'created_by');
    }

    public function certificates()
    {
        return $this->hasMany(CertchainCertificate::class, 'event_id');
    }

    public function getCertificateCountAttribute(): int
    {
        return $this->certificates()->count();
    }
}
