<?php

namespace App\Http\Requests\Coach;

use Illuminate\Foundation\Http\FormRequest;

class WorkoutPlanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],

            // الحفظ يدعم المسودة أو التفعيل فقط.
            'status' => ['sometimes', 'in:draft,active'],

            'days' => ['required', 'array', 'min:1'],

            'days.*.day' => [
                'required',
                'string',
                'distinct',
                'in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            ],

            'days.*.date' => ['nullable', 'date'],
            'days.*.focus' => ['nullable', 'string'],

            // قائمة التمارين داخل كل يوم، ويمكن أن تكون فارغة.
            'days.*.exercises' => ['present', 'array'],

            'days.*.exercises.*.exercise_id' => [
                'bail',
                'nullable',
                'required_without:days.*.exercises.*.custom_exercise_id',
                'integer',
                'exists:exercises,id',
            ],

            'days.*.exercises.*.custom_exercise_id' => [
                'bail',
                'nullable',
                'required_without:days.*.exercises.*.exercise_id',
                'integer',
                'exists:custom_exercises,id',
            ],

            'days.*.exercises.*.sets' => [
                'required',
                'integer',
                'min:1',
            ],

            'days.*.exercises.*.reps' => ['required'],

            'days.*.exercises.*.rest_time' => ['nullable'],

            'days.*.exercises.*.notes' => [
                'nullable',
                'string',
            ],

            'days.*.exercises.*.estimated_duration_seconds' => [
                'required',
                'integer',
                'min:1',
            ],

            'days.*.exercises.*.estimated_calories' => [
                'sometimes',
                'nullable',
                'numeric',
                'min:0',
                'max:999999.99',
            ],
        ];
    }
}