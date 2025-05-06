<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseIndexResource;
use App\Http\Resources\CourseShowResource;
use App\Http\Resources\PaginationResource;
use App\Models\Course;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $courses = Course::with(['user', 'syllabi.lessons'])->paginate(5);
        return $this->successResponse([
            'courses'    => CourseIndexResource::collection($courses),
            'pagination' => new PaginationResource($courses),
        ], 'Courses retrieved successfully');
    }

    public function store(Request $request)
    {
        $course = Course::create($request->all());
        return $this->successResponse(null, 'Course created successfully', 201);
    }

    public function show($id)
    {
        $course = Course::with(['user', 'syllabi.lessons'])->find($id);
        if (!$course) {
            return $this->errorResponse('Course not found', 404);
        }
        return $this->successResponse(['course' => new CourseShowResource($course)], 'Course retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return $this->errorResponse('Course not found', 404);
        }
        $course->update($request->all());
        return $this->successResponse(null, 'Course updated successfully');
    }

    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return $this->errorResponse('Course not found', 404);
        }
        $course->delete();
        return $this->successResponse(null, 'Course deleted successfully');
    }

}
