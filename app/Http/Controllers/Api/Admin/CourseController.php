<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Services\CourseService;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public $courseService;

    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }
    public function index()
    {
        return $this->courseService->index();
    }
    public function store(StoreCourseRequest $request)
    {
        return $this->courseService->store($request);
    }
    public function show($id)
    {
        return $this->courseService->show($id);
    }
    public function update(UpdateCourseRequest $request, $id)
    {
        return $this->courseService->update($request, $id);
    }
    public function destroy($id)
    {
        return $this->courseService->destroy($id);
    }
}
