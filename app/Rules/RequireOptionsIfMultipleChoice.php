<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class RequireOptionsIfMultipleChoice implements Rule
{
    protected $question;

    public function __construct($question)
    {
        $this->question = $question;
    }

    public function passes($attribute, $value): bool
    {
        // If the question type is multiple_choice, options must be a 4-item array
        if (($this->question['question_type'] ?? null) === 'multiple_choice') {
            return is_array($value) && count($value) === 4;
        }

        // Otherwise it's valid (no options required for true_false)
        return true;
    }

    public function message(): string
    {
        return 'The options field is required and must contain exactly 4 items for multiple choice questions.';
    }
}
