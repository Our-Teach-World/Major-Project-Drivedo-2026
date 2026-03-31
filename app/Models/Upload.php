<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    protected $table = 'uploads';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'filename',
        'filepath',
        'extracted_text',
    ];

    public function user()
    {
        return $this->belongsTo(Auth::class, 'user_id');
    }
}
