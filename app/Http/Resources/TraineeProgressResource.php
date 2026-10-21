<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TraineeProgressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                 => $this->id,
            'weight'             => (float) $this->weight,
            'height'             => $this->height,
            'workouts_completed' => $this->workouts_completed ?? 0,
            'notes'              => $this->notes,
            'coach_notes'        => $this->coach_notes,
            'progress_photo'     => $this->progress_photo ? asset('storage/' . $this->progress_photo) : null,
            'recorded_at'        => optional($this->recorded_at)->format('Y-m-d') ?? $this->created_at->format('Y-m-d'),
            'formatted_date'     => optional($this->recorded_at)->translatedFormat('d F Y') ?? $this->created_at->translatedFormat('d F Y'),
        ];
    }
}