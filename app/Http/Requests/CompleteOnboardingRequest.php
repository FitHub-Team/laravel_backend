<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CompleteOnboardingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:10|max:100',
            'height' => 'required|numeric|min:50|max:250',
            'weight' => 'required|numeric|min:20|max:300',
            'health_goal' => 'required|in:weight_loss,muscle_building,maintain_fitness',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|array',
            'dietary_preference' => 'nullable|string',
            'disclaimer_accepted' => 'nullable|boolean',
        ];
    }
}
