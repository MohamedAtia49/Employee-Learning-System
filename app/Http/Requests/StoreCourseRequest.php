<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
class StoreCourseRequest extends FormRequest
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
            'title' => 'required|string',
            'user_id' => 'required|integer',
            'duration' => 'required|integer',
            'description' => 'required|string',
            'video_url' => 'nullable|url',
            'thumbnail_image' => 'nullable|string',
            'syllabus' => 'nullable|array',
            'syllabus.*.title' => 'required|string',
            'syllabus.*.description' => 'required|string',
            'syllabus.*.duration' => 'required|integer',
            'syllabus.*.order' => 'required|integer',
            'syllabus.*.lessons' => 'nullable|array',
            'syllabus.*.lessons.*.title' => 'required|string',
            'syllabus.*.lessons.*.video_url' => 'nullable|url',
            'syllabus.*.lessons.*.duration' => 'required|integer',
            'syllabus.*.lessons.*.is_preview' => 'boolean',
            'syllabus.*.lessons.*.order' => 'required|integer',
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
