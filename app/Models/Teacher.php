<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'student_id',
        'display_name',
        'profile_image',
        'bio',
        'branch',
        'semester',
    ];

    /**
     * The teacher profile belongs to a student (auth) record.
     */
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
