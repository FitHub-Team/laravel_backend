<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoachProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'specialization' => 'required|string|max:255',
            'experience' => 'required|integer|min:0',
            'birth_year' => 'required|date|before:today',
            'location' => 'required|string|max:255',
            'national_id' => 'required|string|max:50',
            'bio' => 'nullable|string',
            'certifications' => 'nullable|array',
        ];
    }
}
