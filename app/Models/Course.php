<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function quiz()
    {
        return $this->hasMany(Quiz::class);
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class,'course_employee');
    }
    public function syllabi()
    {
        return $this->hasMany(Syllabus::class,'course_id');
    }
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class);
    }
    public function lessonProgress()
    {
        return $this->hasManyThrough(
            LessonProgress::class,
            Syllabus::class,
            'course_id',
            'lesson_id',
            'id',
            'id'
        );
    }
}
