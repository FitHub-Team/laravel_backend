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
                'max:999.99'
            ],
            'recorded_at' => [
                'required',
                'date',
                'before_or_equal:today'
            ],
            'progress_photo' => [
                'nullable',
                'mimes:png,jpg'
            ]
        ];
    }
}