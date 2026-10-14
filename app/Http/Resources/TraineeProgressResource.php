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
            'chronic_diseases'    => $this->chronic_diseases ?? 'لا',     // تحتمل "نعم" أو "لا"
            'previous_injuries'   => $this->previous_injuries ?? 'لا',    // تحتمل "نعم" أو "لا"
            'current_medications' => $this->current_medications ?? 'لا', // تحتمل "نعم" أو "لا"
            'other_details'       => $this->other_details ?? null,       // خانة تفاصيل أخرى الموضحة في أسفل الواجهة
        ];
    }
}