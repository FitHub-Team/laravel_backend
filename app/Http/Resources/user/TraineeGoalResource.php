<?php

namespace App\Http\Resources\user;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TraineeGoalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'current_goal'       => $this->goal->title,
            'target_weight'      => $this->target_weight,
            'goal_duration'      => $this->goal_duration,
            'activity_level'     => $this->activityLevel->title,
            'additional_notes'   =>  $this->additional_notes,
        ];
    }
}
