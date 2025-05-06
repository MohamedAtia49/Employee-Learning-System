<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Employee;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    use ApiResponse;
    public function getProgress($employeeId, $courseId)
    {
        $employee = Employee::find($employeeId);
        if (!$employee) {
            return $this->errorResponse('Employee not found', 404);
        }

        $enrollment = $employee->enrollments()->where('course_id', $courseId)->first();
        if (!$enrollment) {
            return $this->errorResponse('Course not found for this employee', 404);
        }

        $progressRecords = $employee->progress()
            ->whereHas('lesson.syllabus', function($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->with('lesson')
            ->get();

        $course = Course::with(['syllabi.lessons'])->find($courseId);
        
        // Calculate total lessons in the course
        $totalLessons = 0;
        foreach ($course->syllabi as $syllabus) {
            $totalLessons += $syllabus->lessons->count();
        }

        $completedLessons = $progressRecords->where('is_completed', true)->count();
        $progressPercentage = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        return $this->successResponse([
            'total_lessons' => $totalLessons,
            'completed_lessons' => $completedLessons,
            'progress_percentage' => $progressPercentage,
        ], 'Employee progress retrieved successfully');
    }
}
