<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'quiz_id'           => $this->id,
            'title'             => $this->title,
            'description'       => $this->description,
            'start_at'          => $this->start_at,
            'end_at'            => $this->end_at,
            'duration_minutes'  => $this->duration_minutes,
            'is_published'      => (bool) $this->is_published,
            'max_attempts'      => $this->max_attempts,
            'creator'           => new CreatorResource($this->user),
            'questions'         => QuestionResource::collection($this->questions),
        ];
    }
}
