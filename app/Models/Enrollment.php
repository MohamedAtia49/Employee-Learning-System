<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
