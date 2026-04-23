<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Timetable extends Model
{
    protected $fillable = [
        'branch',
        'semester',
        'day',
        'start_time',
        'end_time',
        'subject_name',
        'subject_code',
        'teacher_name',
        'room_no'
    ];
}
