<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    // Jo columns form ke through save honge
    protected $fillable = [
        'title',
        'content',
        'attachment_path',
        'target_branch',   // Nullable (agar sabke liye hai)
        'target_semester', // Nullable (agar sabke liye hai)
        'target_role',     // Role targeted (student, teacher, hod)
        'created_by',      // Kis Admin/HOD ne banaya
        'creator_type',    // Admin Class or User Class
    ];

    /**
     * Relationship: Ek notice kisi ek User (Admin/HOD) ne banaya hota hai.
     */
    public function creator()
    {
        return $this->morphTo(__FUNCTION__, 'creator_type', 'created_by');
    }
}