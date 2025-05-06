<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizSubmissionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'attempt'      => $this->attempt_number,
            'score'        => $this->score,
            'rank'         => $this->rank,
            'percentage'   => $this->percentage,
            'average_score'=> (float) $this->quiz->submissions()->avg('score'),
            'total_points' => $this->total_points,
        ];
    }
}
