<?php

namespace App\Http\Requests\Ai;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GenerateWorkoutPlanRequest extends FormRequest
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
            // 'training_days_per_week' => [
            //     'required',
            //     'integer',
            //     'min:1',
            //     'max:7',
            // ],

            // 'split_id' => [
            //     'required',
            //     'string',
            // ],
        ];
    }
}
