<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MentorshipSettings extends Model
{
    use HasFactory;

    protected $table = 'mentorship_settings';

    protected $fillable = [
        'site_name',
        'contact_email',
        'max_requests',
        'session_duration',
        'terms',
        'logo_path',
    ];

    public static function current(): ?self
    {
        return self::first();
    }
}
