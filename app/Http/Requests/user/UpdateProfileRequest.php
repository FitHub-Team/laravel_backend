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
            'profile_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048']
        ];
    }
}
