<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCoachProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'full_name' => 'required|string|max:255',

            'specialization' => 'nullable|string|max:255',
            'experience' => 'nullable|integer|min:0',
            'birth_year' => 'nullable|integer|digits:4|min:1900|max:' . date('Y'),
            'location' => 'nullable|string|max:255',
            'national_id' => 'nullable|string|max:50',

            'bio' => 'nullable|string',

            'skills' => 'nullable|array',
            'skills.*.id' => 'nullable',
            'skills.*.name' => 'nullable|string|max:255',

            'certifications' => 'nullable|array',
            'certifications.*.id' => 'nullable',
            'certifications.*.title' => 'nullable|string|max:255',
            'certifications.*.issuer' => 'nullable|string|max:255',
            'certifications.*.year' => 'nullable|integer|digits:4',
        ];
    }

    public function messages(): array
    {
        return [
            'full_name.required' => 'الاسم الكامل مطلوب.',
            'full_name.string' => 'الاسم الكامل يجب أن يكون نصًا.',
            'full_name.max' => 'الاسم الكامل يجب ألا يتجاوز 255 حرفًا.',

            'specialization.required' => 'التخصص مطلوب.',
            'specialization.string' => 'التخصص يجب أن يكون نصًا.',
            'specialization.max' => 'التخصص يجب ألا يتجاوز 255 حرفًا.',

            'experience.required' => 'سنوات الخبرة مطلوبة.',
            'experience.integer' => 'سنوات الخبرة يجب أن تكون رقمًا صحيحًا.',
            'experience.min' => 'سنوات الخبرة لا يمكن أن تكون أقل من 0.',

            'birth_year.required' => 'سنة الميلاد مطلوبة.',
            'birth_year.integer' => 'سنة الميلاد يجب أن تكون رقمًا صحيحًا.',
            'birth_year.digits' => 'سنة الميلاد يجب أن تتكون من 4 أرقام.',
            'birth_year.min' => 'سنة الميلاد غير صحيحة.',
            'birth_year.max' => 'سنة الميلاد لا يمكن أن تكون في المستقبل.',

            'location.required' => 'الموقع مطلوب.',
            'location.string' => 'الموقع يجب أن يكون نصًا.',
            'location.max' => 'الموقع يجب ألا يتجاوز 255 حرفًا.',

            'national_id.required' => 'رقم الهوية مطلوب.',
            'national_id.string' => 'رقم الهوية يجب أن يكون نصًا.',
            'national_id.max' => 'رقم الهوية يجب ألا يتجاوز 50 حرفًا.',

            'bio.string' => 'النبذة الشخصية يجب أن تكون نصًا.',

            'skills.array' => 'المهارات يجب أن تكون قائمة صحيحة.',
            'skills.*.name.required' => 'اسم المهارة مطلوب.',
            'skills.*.name.string' => 'اسم المهارة يجب أن يكون نصًا.',
            'skills.*.name.max' => 'اسم المهارة يجب ألا يتجاوز 255 حرفًا.',

            'certifications.array' => 'الشهادات يجب أن تكون قائمة صحيحة.',
            'certifications.*.title.required' => 'عنوان الشهادة مطلوب.',
            'certifications.*.title.string' => 'عنوان الشهادة يجب أن يكون نصًا.',
            'certifications.*.title.max' => 'عنوان الشهادة يجب ألا يتجاوز 255 حرفًا.',

            'certifications.*.issuer.string' => 'اسم الجهة المانحة يجب أن يكون نصًا.',
            'certifications.*.issuer.max' => 'اسم الجهة المانحة يجب ألا يتجاوز 255 حرفًا.',

            'certifications.*.year.integer' => 'سنة الحصول على الشهادة يجب أن تكون رقمًا صحيحًا.',
            'certifications.*.year.digits' => 'سنة الحصول على الشهادة يجب أن تتكون من 4 أرقام.',
        ];
    }
}