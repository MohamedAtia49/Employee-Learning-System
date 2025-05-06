<?php

namespace App\Services;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Http\Resources\CourseIndexResource;
use App\Http\Resources\CourseShowResource;
use App\Http\Resources\PaginationResource;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Syllabus;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CourseService {

    use ApiResponse;
    public function index()
    {
        $courses = Course::with(['user', 'syllabi.lessons'])->paginate(5);
        return $this->successResponse([
            'courses'    => CourseIndexResource::collection($courses),
            'pagination' => new PaginationResource($courses),
        ], 'Courses retrieved successfully');
    }

    public function store(StoreCourseRequest $request)
    {
        // Validate the request data
        $validated = $request->validated();

        $courseData = $request->except('syllabus');
        $course = Course::create($courseData);

        if ($request->has('syllabus')) {
            foreach ($request->syllabus as $syllabusData) {
                $lessons = $syllabusData['lessons'] ?? [];
                unset($syllabusData['lessons']);

                $syllabus = Syllabus::create(array_merge($syllabusData, [
                    'course_id' => $course->id,
                ]));

                foreach ($lessons as $lessonData) {
                    Lesson::create(array_merge($lessonData, [
                        'syllabus_id' => $syllabus->id,
                    ]));
                }
            }
        }

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

    public function update(UpdateCourseRequest $request, $id)
    {
        // Find the course or return error
        $course = Course::find($id);
        if (!$course) {
            return $this->errorResponse('Course not found', 404);
        }

        // Validate the request data
        $validated = $request->validated();

        // Update course data
        $courseData = $request->except('syllabus');
        $course->update($courseData);

        if ($request->has('syllabus')) {
            // Get all existing syllabus IDs for this course
            $existingSyllabusIds = $course->syllabi()->pluck('id')->toArray();
            $receivedSyllabusIds = [];

            foreach ($request->syllabus as $syllabusData) {
                $lessons = $syllabusData['lessons'] ?? [];
                unset($syllabusData['lessons']);

                // Update or create syllabus
                if (isset($syllabusData['id'])) {
                    $syllabus = Syllabus::where('id', $syllabusData['id'])
                                        ->where('course_id', $course->id)
                                        ->first();
                    if ($syllabus) {
                        $syllabus->update($syllabusData);
                        $receivedSyllabusIds[] = $syllabus->id;
                    }
                } else {
                    $syllabus = Syllabus::create(array_merge($syllabusData, [
                        'course_id' => $course->id,
                    ]));
                    $receivedSyllabusIds[] = $syllabus->id;
                }

                if ($syllabus) {
                    // Get all existing lesson IDs for this syllabus
                    $existingLessonIds = $syllabus->lessons()->pluck('id')->toArray();
                    $receivedLessonIds = [];

                    foreach ($lessons as $lessonData) {
                        // Update or create lesson
                        if (isset($lessonData['id'])) {
                            $lesson = Lesson::where('id', $lessonData['id'])
                                            ->where('syllabus_id', $syllabus->id)
                                            ->first();
                            if ($lesson) {
                                $lesson->update($lessonData);
                                $receivedLessonIds[] = $lesson->id;
                            }
                        } else {
                            $lesson = Lesson::create(array_merge($lessonData, [
                                'syllabus_id' => $syllabus->id,
                            ]));
                            $receivedLessonIds[] = $lesson->id;
                        }
                    }

                    // Delete lessons that weren't in the request
                    $lessonsToDelete = array_diff($existingLessonIds, $receivedLessonIds);
                    if (!empty($lessonsToDelete)) {
                        Lesson::whereIn('id', $lessonsToDelete)->delete();
                    }
                }
            }

            // Delete syllabus that weren't in the request
            $syllabusToDelete = array_diff($existingSyllabusIds, $receivedSyllabusIds);
            if (!empty($syllabusToDelete)) {
                Syllabus::whereIn('id', $syllabusToDelete)->delete();
            }
        } else {
            // If no syllabus in request, delete all existing syllabus for this course
            $course->syllabus()->delete();
        }

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
