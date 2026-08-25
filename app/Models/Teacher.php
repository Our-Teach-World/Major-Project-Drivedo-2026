<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'user_id',
        'display_name',
        'profile_image',
        'bio',
        'branch',
        'semester',
    ];

    /**
     * The teacher profile belongs to a user (auth) record.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected $casts = [
        'semester' => 'array',
    ];

}
