<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    public function syllabus()
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }
    public function progress()
    {
        return $this->hasMany(LessonProgress::class);
    }
}
