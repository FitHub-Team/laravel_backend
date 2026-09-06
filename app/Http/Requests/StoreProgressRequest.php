<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProgressRequest extends FormRequest
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
            'weight' => [
                'required',
                'numeric',
                'min:0',
                'max:999.99'],
                'height' => ['nullable', 'numeric', 'min:0', 'max:999.99'],
                //لاحقا دغيرها ل text 
                'notes' => ['nullable', 'string',],
                'recorded_at' => ['required', 'date', 'before_or_equal:today'],

                'exercises' => ['required', 'array', 'min:1',],

                'exercises.*.workout_exercise_id' => ['required', 'integer', 'exists:workout_exercises,id'],
                'exercises.*.completed' => [
                    'required',
                    'boolean',
                ],

                'exercises.*.completed_sets' => ['nullable', 'integer', 'min:0'],

                'exercises.*.completed_reps' => ['nullable', 'integer', 'min:0'],

                'exercises.*.notes' => ['nullable', 'string'],

            ];
        
    }
}
