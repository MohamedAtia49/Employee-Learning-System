<?php

namespace App\Services;

use App\Http\Requests\StoreQuizRequest;
use App\Http\Requests\UpdateQuizRequest;
use App\Http\Resources\QuizResource;
use App\Models\Quiz;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class QuizService{
    use ApiResponse;

    public function index()
    {
        return $this->successResponse(Quiz::active()->get(), 'Display all active quizzes');
    }

    public function store(StoreQuizRequest $request)
    {
        $validated = $request->validated();

        \DB::beginTransaction();
        try {
            // Create the quiz
            $quiz = Quiz::create([
                'course_id' => $validated['course_id'],
                'title' => $validated['title'],
                'description' => $validated['description'],
                'start_at' => $validated['start_at'] ?? null,
                'end_at' => $validated['end_at'] ?? null,
                'duration_minutes' => $validated['duration_minutes'] ?? null,
                'is_published' => $validated['is_published'] ?? false,
                'max_attempts' => $validated['max_attempts'] ?? 1,
                'user_id' => auth()->id(),
            ]);

            // Create questions and options
            foreach ($validated['questions'] as $questionData) {
                $question = $quiz->questions()->create([
                    'question' => $questionData['question'],
                    'question_type' => $questionData['question_type'],
                    'points' => $questionData['points'],
                ]);

                // Only create options for multiple_choice questions
                if ($questionData['question_type'] === 'multiple_choice') {
                    foreach ($questionData['options'] as $optionData) {
                        $question->options()->create([
                            'option_text' => $optionData['option_text'],
                            'is_correct' => $optionData['is_correct'],
                        ]);
                    }
                }
            }

            \DB::commit();

            return $this->successResponse(
                $quiz->load(['questions', 'questions.options']),
                'Quiz created successfully',
                201
            );
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->errorResponse('Failed to create quiz: ' . $e->getMessage(), 500);
        }
    }

    public function show($quiz)
    {
        // Eager loading for better performance
        $quiz = Quiz::with(['user', 'questions.options'])->find($quiz);

        return $this->successResponse(new QuizResource($quiz), 'Display a specific quiz');
    }
    public function update(UpdateQuizRequest $request, $id)
    {
        $validated = $request->validated();

        $quiz = Quiz::with(['questions', 'questions.options'])->find($id);

        if (!$quiz) {
            return $this->errorResponse('Quiz not found', 404);
        }

        \DB::beginTransaction();
        try {
            // Update quiz details
            $quiz->update([
                'course_id' => $validated['course_id']?? $quiz->course_id,
                'title' => $validated['title'] ?? $quiz->title,
                'description' => $validated['description'] ?? $quiz->description,
                'start_at' => $validated['start_at'] ?? $quiz->start_at,
                'end_at' => $validated['end_at'] ?? $quiz->end_at,
                'duration_minutes' => $validated['duration_minutes'] ?? $quiz->duration_minutes,
                'is_published' => $validated['is_published'] ?? $quiz->is_published,
                'max_attempts' => $validated['max_attempts'] ?? $quiz->max_attempts,
            ]);

            // Handle questions if provided
            if (isset($validated['questions'])) {
                $existingQuestionIds = $quiz->questions->pluck('id')->toArray();
                $updatedQuestionIds = [];

                foreach ($validated['questions'] as $questionData) {
                    // Update existing question or create new one
                    if (isset($questionData['id'])) {
                        $question = $quiz->questions()->find($questionData['id']);
                        if ($question) {
                            $question->update([
                                'question' => $questionData['question'],
                                'question_type' => $questionData['question_type'],
                                'points' => $questionData['points'],
                            ]);
                            $updatedQuestionIds[] = $question->id;
                        }
                    } else {
                        $question = $quiz->questions()->create([
                            'question' => $questionData['question'],
                            'question_type' => $questionData['question_type'],
                            'points' => $questionData['points'],
                        ]);
                        $updatedQuestionIds[] = $question->id;
                    }

                    // Handle options for multiple_choice questions
                    if ($questionData['question_type'] === 'multiple_choice' && isset($questionData['options'])) {
                        $existingOptionIds = $question->options->pluck('id')->toArray();
                        $updatedOptionIds = [];

                        foreach ($questionData['options'] as $optionData) {
                            if (isset($optionData['id'])) {
                                $option = $question->options()->find($optionData['id']);
                                if ($option) {
                                    $option->update([
                                        'option_text' => $optionData['option_text'],
                                        'is_correct' => $optionData['is_correct'],
                                    ]);
                                    $updatedOptionIds[] = $option->id;
                                }
                            } else {
                                $option = $question->options()->create([
                                    'option_text' => $optionData['option_text'],
                                    'is_correct' => $optionData['is_correct'],
                                ]);
                                $updatedOptionIds[] = $option->id;
                            }
                        }

                        // Delete options that weren't included in the update
                        $optionsToDelete = array_diff($existingOptionIds, $updatedOptionIds);
                        if (!empty($optionsToDelete)) {
                            $question->options()->whereIn('id', $optionsToDelete)->delete();
                        }
                    }
                }

                // Delete questions that weren't included in the update
                $questionsToDelete = array_diff($existingQuestionIds, $updatedQuestionIds);
                if (!empty($questionsToDelete)) {
                    $quiz->questions()->whereIn('id', $questionsToDelete)->delete();
                }
            }

            \DB::commit();

            return $this->successResponse(
                $quiz->fresh(['questions', 'questions.options']),
                'Quiz updated successfully'
            );
        } catch (\Exception $e) {
            \DB::rollBack();
            return $this->errorResponse('Failed to update quiz: ' . $e->getMessage(), 500);
        }
    }
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        if (!$quiz) {
            return $this->errorResponse('Quiz not found', 404);
        }
        $quiz->delete();
        return $this->successResponse(null, 'Quiz deleted successfully');
    }
}
