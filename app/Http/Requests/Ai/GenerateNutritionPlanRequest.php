<?php

namespace App\Http\Requests\Ai;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GenerateNutritionPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trainee_id' => ['nullable', 'integer', 'exists:users,id'],
            'save_as_draft' => ['nullable', 'boolean'],
            'pace' => ['nullable', 'string'],
            'meals_per_day' => [
                'nullable',
                'integer',
                'min:1',
                'max:8',
            ],
            'allergies' => ['nullable', 'array'],
            'allergies.*' => ['string'],
            'medical_conditions' => ['nullable', 'array'],
            'medical_conditions.*' => ['string'],
        ];
    }
}
