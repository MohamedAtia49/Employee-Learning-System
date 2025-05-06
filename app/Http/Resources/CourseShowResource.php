<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseShowResource extends JsonResource
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
            'thumbnail_image'=> $this->thumbnail_image ? asset('storage/' . $this->thumbnail_image) : null,
            'description'=> $this->description,
            'video_url'=> $this->video_url,
            'instructor' => $this->user->name,
            'duration' => $this->duration,
            'syllabi' => SyllabusResource::collection($this->syllabi),
        ];
    }
}
