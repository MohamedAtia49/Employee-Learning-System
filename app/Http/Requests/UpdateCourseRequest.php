<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCourseRequest extends FormRequest
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
            'title' => 'sometimes|required|string',
            'user_id' => 'sometimes|required|integer',
            'duration' => 'sometimes|required|integer',
            'description' => 'sometimes|required|string',
            'video_url' => 'nullable|url',
            'thumbnail_image' => 'nullable|string',
            'syllabus' => 'nullable|array',
            'syllabus.*.id' => 'sometimes|integer',
            'syllabus.*.title' => 'required_with:syllabus|string',
            'syllabus.*.description' => 'required_with:syllabus|string',
            'syllabus.*.duration' => 'required_with:syllabus|integer',
            'syllabus.*.order' => 'required_with:syllabus|integer',
            'syllabus.*.lessons' => 'nullable|array',
            'syllabus.*.lessons.*.id' => 'sometimes|integer',
            'syllabus.*.lessons.*.title' => 'required_with:syllabus.*.lessons|string',
            'syllabus.*.lessons.*.video_url' => 'nullable|url',
            'syllabus.*.lessons.*.duration' => 'required_with:syllabus.*.lessons|integer',
            'syllabus.*.lessons.*.is_preview' => 'boolean',
            'syllabus.*.lessons.*.order' => 'required_with:syllabus.*.lessons|integer',
            'syllabus.*.lessons.*._destroy' => 'sometimes|boolean',
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
