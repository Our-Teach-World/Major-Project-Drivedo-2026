<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    protected $table = 'students';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'role',
        'status',
        'branch',
        'semester',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function uploads()
    {
        return $this->hasMany(Upload::class, 'user_id');
    }

    /**
     * Teacher profile record (only exists if role === 'teacher')
     */
    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class, 'student_id');
    }
}
