<?php

namespace App\Http\Requests\Coach;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CustomExerciseRequest extends FormRequest
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
            'name'         => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'muscle_group' => ['required', 'string', 'max:255'],
            'equipment'    => ['nullable', 'string', 'max:255'],
            'difficulty'   => ['required', 'string', 'max:255'],
            'instructions' => ['required', 'string'],
        ];
    }
}
