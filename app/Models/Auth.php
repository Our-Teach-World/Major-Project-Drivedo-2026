<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Auth extends Authenticatable
{
    protected $table = 'auth';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'password',
        'role',
        'status',
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
}
