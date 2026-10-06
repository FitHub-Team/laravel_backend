<?php

namespace App\Http\Requests\Coach;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class WorkoutPlanRequest extends FormRequest
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
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',

            'status' => 'sometimes|in:draft,active,completed,cancelled',

            'exercises' => 'required|array',
            'exercises.*.day_of_week' => 'required|string',
            'exercises.*.exercise_id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'required|integer',
            'exercises.*.reps' => 'required|integer',
            'exercises.*.rest_time' => 'nullable',
            'exercises.*.notes' => 'nullable|string',
            'days' => 'required|array|min:1',
            'days.*.day' => 'required|string|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'days.*.focus' => 'nullable|string',
        ];
    }
}
