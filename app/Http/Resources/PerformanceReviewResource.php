<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin \App\Models\PerformanceReview
 */
class PerformanceReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'employee' => new EmployeeResource($this->whenLoaded('employee')),
            'reviewer' => new EmployeeResource($this->whenLoaded('reviewer')),
            'review_period' => $this->review_period,
            'ratings' => [
                'quality_of_work' => $this->quality_of_work,
                'productivity' => $this->productivity,
                'communication' => $this->communication,
                'teamwork' => $this->teamwork,
                'leadership' => $this->leadership,
            ],
            'overall_rating' => $this->overall_rating,
            'strengths' => $this->strengths,
            'areas_for_improvement' => $this->areas_for_improvement,
            'goals' => $this->goals,
            'comments' => $this->comments,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
