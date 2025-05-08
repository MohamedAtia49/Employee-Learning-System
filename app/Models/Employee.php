<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $table = 'employees';

    public function courses()
    {
        return $this->belongsToMany(Course::class,'course_employee');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
    public function quizSubmissions() {
        return $this->hasMany(QuizSubmission::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }

    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quiz::class);
    }

}
