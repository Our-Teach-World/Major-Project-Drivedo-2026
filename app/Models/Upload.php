<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = 'uploads';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'semester',
        'filename',
        'filepath',
        'extracted_text',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
