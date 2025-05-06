<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class StoreQuizRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
            'max_attempts' => 'integer|min:1',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.question_type' => 'required|in:multiple_choice,true_false',
            'questions.*.points' => 'required|integer|min:1',
            'questions.*.options' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    $input = request()->all();
                    $index = explode('.', $attribute)[1];
                    $question = $input['questions'][$index] ?? null;

                    if ($question) {
                        $rule = new \App\Rules\RequireOptionsIfMultipleChoice($question);
                        if (! $rule->passes($attribute, $value)) {
                            $fail($rule->message());
                        }
                    }
                },
            ],
            'questions.*.options.*.option_text' => 'required_if:questions.*.question_type,multiple_choice|string',
            'questions.*.options.*.is_correct' => 'required_if:questions.*.question_type,multiple_choice|boolean',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors()
        ], 422));
    }
}
