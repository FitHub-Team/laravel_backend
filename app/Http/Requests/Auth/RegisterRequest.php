<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'full_name' => 'required|string|max:100',
            'email'     => 'required|string|email|max:150|unique:users',
            'password'  => 'required|string|min:6',
            'role' => 'required|in:user,coach',
            // coach register 
            // 'identity_number' => 'required_if:role,coach|string|max:50',
            'specialization' => 'sometimes|string|max:150',
            'experience' => 'sometimes|integer|min:0',
            'location' => 'sometimes|string|max:150',
            'birth_year' => 'sometimes|integer|min:1900|max:' . date('Y'),

            //user register
          'gender' => 'sometimes|in:male,female',
            'date_of_birth' => 'sometimes|date',
            'height' => 'sometimes|numeric|min:0',
            'weight' => 'sometimes|numeric|min:0',
            'health_goal' => 'sometimes|in:weight_loss,muscle_building,maintain_weight,improve_endurance',
            'medical_conditions' => 'sometimes|string',
            'allergies' => 'sometimes|string',
            'dietary_preference' => 'sometimes|string|max:100',
            'profile_photo' => 'sometimes|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }
}
