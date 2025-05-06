<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonProgress extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
