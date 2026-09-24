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
            'full_name' => ['required', 'string', 'max:100'],
            'gender' => ['sometimes', 'in:male,female'],
            'date_of_birth' => ['sometimes', 'date'],
            'height' => ['sometimes', 'numeric', 'min:0', 'max:999.99'],
            'weight' => ['sometimes', 'numeric', 'min:0', 'max:999.99'],
            'goal_id' => ['sometimes', 'integer', 'exists:goals,id'],
            'activity_level_id' => ['sometimes', 'integer', 'exists:activity_levels,id'],
            'health_condition_ids' => ['sometimes', 'array'],
            'health_condition_ids.*' => [
                'integer',
                'exists:health_conditions,id'
            ],
            'dietary_restriction_ids' => [
                'sometimes',
                'array'
            ],
            'dietary_restriction_ids.*' => [
                'integer',
                'exists:dietary_restrictions,id'
            ],
            'health_condition_note' => ['sometimes', 'nullable'],
            'dietary_restriction_note' => ['sometimes', 'nullable'],
            'training_location_id' => ['sometimes', 'integer', 'exists:training_locations,id'],
            'available_days' => ['sometimes', 'array'],
            'available_days.*' => ['string'],
            'trainer_type' => ['sometimes', 'in:ai,human'],
            'disclaimer_accepted' => ['sometimes', 'boolean'],

        ];
    }
}
