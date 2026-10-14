<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRecipeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'meal_type' => [
                'sometimes',
                'required',
                'in:breakfast,lunch,dinner,snack',
            ],

            'calories' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'protein' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'carbs' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'fat' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'prep_time' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'servings' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'ingredients' => [
                'sometimes',
                'required',
                'array',
                'min:1',
            ],

            'ingredients.*.name' => [
                'required',
                'string',
                'max:255',
            ],

            'ingredients.*.quantity' => [
                'nullable',
                'numeric',
                'min:0',
            ],

            'ingredients.*.unit' => [
                'nullable',
                'string',
                'max:50',
            ],

            'instructions' => [
                'nullable',
                'array',
            ],

            'instructions.*' => [
                'string',
            ],

            'image' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],

            'is_public' => [
                'nullable',
                'boolean',
            ],
        ];
    }
}
