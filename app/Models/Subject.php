<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    // Mass assignment ke liye allowed columns
    protected $fillable = [
        'name', 
        'code', 
        'semester', 
        'branch',
    ];

    // Ek subject ko multiple teachers padha sakte hain (Pivot table ke through)
    public function teachers()
    {
        return $this->belongsToMany(User::class, 'subject_teacher', 'subject_id', 'user_id')
                    ->withPivot('academic_year')
                    ->withTimestamps();
    }

    // Ek subject ki bahut saari attendance records hongi
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}