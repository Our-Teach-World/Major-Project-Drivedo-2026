<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $table = 'students';
    public $timestamps = false; // We dropped created_at and updated_at originally, or did we? The user data migration kept it in users, we can just disable timestamps for students if they don't have it. Actually keeping false is fine since it was false before in this model class. Wait, no, the migration 2026_03_26_173101_create_auth_table.php had created_at and updated_at! But this class had timestamps = false. I'll leave timestamps = false.

    protected $fillable = [
        'user_id',
        'branch',
        'semester',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
