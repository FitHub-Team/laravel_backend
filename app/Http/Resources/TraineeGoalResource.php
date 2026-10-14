<?php

namespace App\Http\Resources;

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
           'current_goal'       => $this->goal ? $this->goal->title : 'خسارة الوزن',
            'target_weight'      => $this->target_weight ?? '70 كجم',
            'goal_duration'      => $this->goal_duration ?? '3 أشهر',
            'activity_level'     => $this->activityLevel ? $this->activityLevel->name : 'متوسط النشاط',
            'additional_notes'   => $this->notes ?? $this->health_condition_note,
        ];
    }
}