<?php

namespace App\Http\Requests\Ai;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GenerateNutritionPlanRequest extends FormRequest
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
          // البيانات الاختيارية     
            'pace' => [
                'nullable',
            ],
            // تحديد عدد الوجبات في اليوم 
            'meals_per_day' => [
                'nullable',
                'integer',
                'min:1',
                'max:8',
            ],
            // حساسية 
            'allergies' => ['nullable', 'array'],
            'allergies.*' => ['string'],
            // قيود صحية 
            'medical_conditions' => ['nullable', 'array'],
            'medical_conditions.*' => ['string'],
        ];
    }
}
