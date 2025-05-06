<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'quiz_id',
        'score',
        'total_points',
        'percentage',
        'rank',
        'attempt_number',
        'started_at',
        'submitted_at'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers()
    {
        return $this->hasMany(QuizAnswer::class);
    }

    public function getScorePercentageAttribute()
    {
        return $this->score > 0 ? round(($this->score / $this->total_points) * 100, 2) : 0;
    }
}
