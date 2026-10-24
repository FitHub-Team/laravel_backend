<?php

namespace App\Http\Requests\Coach;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class NutritionPlanRequest extends FormRequest
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
            'title' => 'required|string|max:255',
            'daily_calories' => 'nullable|integer|min:0',
            'protein' => 'nullable|integer|min:0',
            'carbs' => 'nullable|integer|min:0',
            'fat' => 'nullable|integer|min:0',
            'notes' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'status' => 'sometimes|in:draft,active,completed,cancelled',

            'meals' => 'required|array|min:1',
            'meals.*.meal_name' => 'required|string|max:255',
            'meals.*.components' => 'nullable|string',
            'meals.*.calories' => 'nullable|integer|min:0',
            'meals.*.protein' => 'nullable|integer|min:0',
            'meals.*.carbs' => 'nullable|integer|min:0',
            'meals.*.fat' => 'nullable|integer|min:0',
            'meals.*.time_to_eat' => 'nullable|string|max:50',
            'meals.*.recipe_id' => 'nullable|integer|exists:recipes,id',
            'meals.*.sort_order' => 'nullable|integer|min:0',
        ];
    }
}
