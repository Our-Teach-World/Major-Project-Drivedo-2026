<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use Notifiable;

    protected $table = 'admin';
    public $timestamps = false;

    protected $fillable = [
        'username',
        'email',
        'password',
        'branch',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
    ];
}
