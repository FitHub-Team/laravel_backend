<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProfileRequest extends FormRequest
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
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');
        return [
            'gender' => $isUpdate
                ? 'sometimes|in:male,female'
                : 'required|in:male,female',

            'age' => $isUpdate
                ? 'sometimes|integer|min:10|max:100'
                : 'required|integer|min:10|max:100',

            'height' => $isUpdate
                ? 'sometimes|numeric|min:50|max:250'
                : 'required|numeric|min:50|max:250',

            'weight' => $isUpdate
                ? 'sometimes|numeric|min:20|max:300'
                : 'required|numeric|min:20|max:300',

            'health_goal' => $isUpdate
                ? 'sometimes|in:weight_loss,muscle_building,maintain_weight,improve_endurance'
                : 'required|in:weight_loss,muscle_building,maintain_weight,improve_endurance',

            'medical_conditions' => 'nullable|string',

            'allergies' => 'nullable|array',

            'dietary_preference' => 'nullable|string',

            'disclaimer_accepted' => $isUpdate
                ? 'sometimes|boolean'
                : 'required|boolean',

            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',

            'date_of_birth' => $isUpdate
                ? 'sometimes|date|before:today'
                : 'nullable|date|before:today',
        ];
    }
}
