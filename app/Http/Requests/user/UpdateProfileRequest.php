<?php

namespace App\Http\Requests\user;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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

            'gender' => 'sometimes|in:male,female',
            'height' => 'sometimes|numeric|min:50|max:250',
            'weight' => 'sometimes|numeric|min:20|max:300',

            'health_goal' => 'sometimes|in:weight_loss,muscle_building,maintain_weight,improve_endurance',

            'medical_conditions' => 'sometimes|nullable|string',

            'allergies' => 'sometimes|nullable|array',
            'allergies.*' => 'string',

            'dietary_preference' => 'sometimes|nullable|string',

            'disclaimer_accepted' => 'sometimes|boolean',

            'profile_photo' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            'date_of_birth' => 'sometimes|date|before:today',
        ];
    }
}
