<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateQuizRequest extends FormRequest
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
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'start_at' => 'nullable|date',
            'end_at' => 'nullable|date|after_or_equal:start_at',
            'duration_minutes' => 'nullable|integer|min:1',
            'is_published' => 'boolean',
            'max_attempts' => 'integer|min:1',

            'questions' => 'sometimes|array',
            'questions.*.id' => 'sometimes|exists:questions,id',
            'questions.*.question' => 'required_with:questions|string',
            'questions.*.question_type' => 'required_with:questions|in:multiple_choice,true_false',
            'questions.*.points' => 'required_with:questions|integer|min:1',

            'questions.*.options' => [
                'nullable', // allows flexibility for true_false
                function ($attribute, $value, $fail) {
                    $input = request()->all();
                    $index = explode('.', $attribute)[1] ?? null;
                    $question = $input['questions'][$index] ?? null;

                    if ($question) {
                        $rule = new \App\Rules\RequireOptionsIfMultipleChoice($question);
                        if (! $rule->passes($attribute, $value)) {
                            $fail($rule->message());
                        }
                    }
                },
            ],

            'questions.*.options.*.id' => 'sometimes|exists:question_options,id',
            'questions.*.options.*.option_text' => 'required_with:questions.*.options|string',
            'questions.*.options.*.is_correct' => 'required_with:questions.*.options|boolean',
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
