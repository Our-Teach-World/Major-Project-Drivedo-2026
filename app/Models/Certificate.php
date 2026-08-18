<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'certificate_id', 'event_id', 'template_id', 'issued_by',
        'student_name', 'student_email', 'enrollment_number',
        'student_branch', 'student_year', 'achievement', 'description',
        'issued_date', 'pdf_path', 'qr_code_path',
        'status', 'revoke_reason', 'email_sent', 'email_sent_at',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'email_sent' => 'boolean',
        'email_sent_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function template()
    {
        return $this->belongsTo(CertificateTemplate::class);
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function blockchainBlock()
    {
        return $this->hasOne(BlockchainBlock::class);
    }

    public function isValid(): bool
    {
        return $this->status === 'issued' && $this->blockchainBlock !== null;
    }

    public function getVerificationUrlAttribute(): string
    {
        return route('verify.certificate', ['id' => $this->certificate_id]);
    }
}
