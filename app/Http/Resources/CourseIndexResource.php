<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseIndexResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id,
            'title'=> $this->title,
            'description'=> $this->description,
            'thumbnail_image'=> $this->thumbnail_image ? asset('storage/' . $this->thumbnail_image) : null,
            'instructor' => $this->user->name,
            'duration' => $this->duration,
            'video_intro' => $this->video_url,
            'num_syllabi' => $this->syllabi->count(),
            'num_lessons' => $this->syllabi->sum(function ($syllabus) {
                return $syllabus->lessons->count();
            }),
        ];
    }
}
