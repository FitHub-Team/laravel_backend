<?php

namespace App\Http\Requests\Coach;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WorkoutPlanRequest extends FormRequest
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',

            'status' => 'sometimes|in:draft,active,completed,cancelled',

            'exercises' => 'required|array',

            'exercises.*.day_of_week' => 'required|string',

            'exercises.*.exercise_id' => [
                'nullable',
                'required_without:exercises.*.custom_exercise_id',
                'integer',
                'exists:exercises,id',
            ],

            'exercises.*.custom_exercise_id' => [
                'nullable',
                'required_without:exercises.*.exercise_id',
                'integer',
                'exists:custom_exercises,id',
            ],

            'exercises.*.sets' => 'required|integer|min:1',
            'exercises.*.reps' => 'required',
            'exercises.*.rest_time' => 'nullable',
            'exercises.*.notes' => 'nullable|string',

            'days' => 'required|array|min:1',

            'days.*.day' =>
                'required|string|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',

            'days.*.focus' => 'nullable|string',
        ];
    }
}