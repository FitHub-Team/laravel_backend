<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthInformationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'health_status'       => $this->health_status,
            'chronic_diseases'    => $this->chronic_diseases,
            'previous_injuries'   => $this->previous_injuries,
            'current_medications' => $this->current_medications,
            'health_condition_note' => $this->health_condition_note, 
        ];
    }
}
