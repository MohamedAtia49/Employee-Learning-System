<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Models\Quiz;
use App\Services\QuizService;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function index()
    {
        return $this->quizService->index();
    }

    public function store(StoreQuizRequest $request)
    {
        return $this->quizService->store($request);
    }

    public function show($id)
    {
        return $this->quizService->show($id);
    }

    public function update(UpdateQuizRequest $request, $id)
    {
        return $this->quizService->update($request, $id);
    }

    public function destroy($id)
    {
        return $this->quizService->destroy($id);
    }
}
