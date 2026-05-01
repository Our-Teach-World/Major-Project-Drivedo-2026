<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['username', 'password', 'role', 'status', 'name', 'email'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Teacher ke liye: Uske subjects fetch karne ke liye
    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'subject_teacher', 'user_id', 'subject_id')
            ->withPivot('academic_year')
            ->withTimestamps();
    }

    // Student ke liye: Uski attendance fetch karne ke liye
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    // Profile for student role
    public function studentProfile()
    {
        return $this->hasOne(Student::class, 'user_id');
    }

    // Profile for teacher role
    public function teacherProfile()
    {
        return $this->hasOne(Teacher::class, 'user_id');
    }

    public function uploads()
    {
        return $this->hasMany(Upload::class, 'user_id');
    }

    // Mentorship relations
    public function mentorshipRequests()
    {
        return $this->hasMany(MentorshipRequest::class, 'student_id');
    }

    public function alumniRequests()
    {
        return $this->hasMany(MentorshipRequest::class, 'alumni_id');
    }

    public function studentSessions()
    {
        return $this->hasMany(MentorshipSession::class, 'student_id');
    }

    public function alumniSessions()
    {
        return $this->hasMany(MentorshipSession::class, 'alumni_id');
    }
}


