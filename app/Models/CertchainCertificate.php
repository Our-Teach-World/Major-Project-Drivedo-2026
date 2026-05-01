<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CertchainCertificate extends Model
{
    protected $table = 'certchain_certificates';

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
        return $this->belongsTo(CertchainEvent::class, 'event_id');
    }

    public function template()
    {
        return $this->belongsTo(CertchainTemplate::class, 'template_id');
    }

    public function issuer()
    {
        return $this->belongsTo(User::class, 'issued_by');
    }

    public function blockchainBlock()
    {
        return $this->hasOne(CertchainBlock::class, 'certificate_id');
    }

    public function isValid(): bool
    {
        return $this->status === 'issued' && $this->blockchainBlock !== null;
    }

    public function getVerificationUrlAttribute(): string
    {
        // Will define verify.certificate route later
        return route('verify.certificate', ['id' => $this->certificate_id]);
    }
}
