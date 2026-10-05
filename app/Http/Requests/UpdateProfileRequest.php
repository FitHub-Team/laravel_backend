<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; //
    }

    public function rules(): array
    {
        $userId = $this->user()?->id;

        return [
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', "unique:users,email,{$userId}"],
            'phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:male,female,ذكر,أنثى'], 
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'], // رفع الصورة
        ];
    }
}
