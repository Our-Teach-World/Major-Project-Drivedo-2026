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
        'name',
        'image_path',
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
