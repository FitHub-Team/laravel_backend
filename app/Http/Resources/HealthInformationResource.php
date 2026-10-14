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
            'health_status'       => $this->health_status ?? 'جيد',
            'chronic_diseases'    => $this->chronic_diseases ?? 'لا',
            'previous_injuries'   => $this->previous_injuries ?? 'لا',
            'current_medications' => $this->current_medications ?? 'لا',
            'other_details'       => $this->other_details ?? $this->health_condition_note, // ربطها مع الحقل الموجود لديك إن وجد
        ];
    }
}