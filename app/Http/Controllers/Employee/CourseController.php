<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function courses()
    {
        $courses = Course::with(['user', 'syllabi.lessons'])->paginate(10);
        return view('employee.courses.index', compact('courses'));
    }

    public function myCourses()
    {
        $employee = Auth::guard('employee')->user();

        $courses = $employee->enrollments()
            ->with([
                'course.reviews',
                'course.instructor',
                'course.image',
                'course.enrollments'
            ])
            ->paginate(10)
            ->pluck('course');

        return view('employee.courses.my_courses', compact('courses'));
    }

}
