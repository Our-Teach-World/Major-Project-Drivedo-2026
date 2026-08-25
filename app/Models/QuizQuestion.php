<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $fillable = [
        'quiz_id', 'question_text', 
        'option1', 'option2', 'option3', 'option4', 
        'correct_option'
    ];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
