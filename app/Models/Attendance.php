<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Form se jo data direct database me jayega, usko yaha define karna zaroori hai
    protected $fillable = [
        'student_id',
        'teacher_id',
        'subject_id',
        'attendance_date',
        'status',
    ];

    // Ye attendance kis student ki hai
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    // Ye attendance kis teacher ne li hai
    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    // Ye attendance kis subject ki hai
    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
}