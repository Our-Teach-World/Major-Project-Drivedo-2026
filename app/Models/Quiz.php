<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['title', 'subject', 'created_by', 'status', 'duration_minutes'];

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class);
    }

    public function results()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
