<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'full_name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'email', 'max:150',  'ends_with:@gmail.com', 'unique:users,email',],
            'password' => [
                'required',
                'string',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
            'role' => ['required', 'in:user,coach'],

            // Coach registration

            'specialization' => ['sometimes', 'string', 'max:150'],
            'experience' => ['sometimes', 'integer', 'min:0',],
            'location' => ['sometimes', 'string', 'max:150',],
            'birth_year' => [
                'sometimes',
                'integer',
                'min:1900',
                'max:' . date('Y'),
            ],
            'price' => [
                'sometimes',
                'numeric',
                'min:0',
            ],

            // User profile

            'gender' => ['sometimes', 'in:male,female',],
            'date_of_birth' => ['sometimes', 'date',],
            'height' => ['sometimes', 'numeric', 'min:0', 'max:999.99',],
            'weight' => ['sometimes', 'numeric', 'min:0', 'max:999.99',],
            'goal_id' => ['sometimes', 'integer', 'exists:goals,id',],
            'activity_level_id' => ['sometimes', 'integer', 'exists:activity_levels,id'],
            'health_condition_ids' => ['sometimes', 'array',],
            'health_condition_ids.*' => [
                'integer',
                'exists:health_conditions,id',
            ],
            'dietary_restriction_ids' => [
                'sometimes',
                'array',
            ],
            'dietary_restriction_ids.*' => [
                'integer',
                'exists:dietary_restrictions,id',
            ],
            'health_condition_note' => ['sometimes', 'nullable', 'text',],
            'dietary_restriction_note' => ['sometimes', 'nullable', 'text',],
            'training_location_id' => ['sometimes', 'integer', 'exists:training_locations,id',],
            'available_days' => ['sometimes', 'array',],
            'available_days.*' => ['string',],
            'trainer_type' => ['sometimes', 'in:ai,human',],
            'disclaimer_accepted' => ['sometimes', 'boolean',],
            'profile_photo' => ['sometimes', 'image', 'mimes:jpg,jpeg,png', 'max:2048',],
        ];
    }
    public function messages(): array
    {
        return [
            'full_name.required' => 'الاسم الكامل مطلوب.',
            'full_name.string' => 'الاسم الكامل يجب أن يكون نصًا.',
            'full_name.max' => 'الاسم الكامل يجب ألا يتجاوز 100 حرف.',

            'email.required' => 'البريد الإلكتروني مطلوب.',
            'email.email' => 'يرجى إدخال بريد إلكتروني صحيح.',
            'email.ends_with' => 'يجب أن يكون البريد الإلكتروني من نوع Gmail.',
            'email.unique' => 'البريد الإلكتروني مستخدم بالفعل.',

            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تحتوي على 8 أحرف على الأقل.',
            'password.mixed' => 'كلمة المرور يجب أن تحتوي على أحرف كبيرة وصغيرة.',
            'password.numbers' => 'كلمة المرور يجب أن تحتوي على رقم واحد على الأقل.',
            'password.symbols' => 'كلمة المرور يجب أن تحتوي على رمز واحد على الأقل.',

            'role.required' => 'نوع الحساب مطلوب.',
            'role.in' => 'نوع الحساب غير صالح.',

            'experience.integer' => 'سنوات الخبرة يجب أن تكون رقمًا صحيحًا.',
            'experience.min' => 'سنوات الخبرة لا يمكن أن تكون سالبة.',

            'birth_year.integer' => 'سنة الميلاد يجب أن تكون رقمًا صحيحًا.',
            'birth_year.min' => 'سنة الميلاد غير صالحة.',
            'birth_year.max' => 'سنة الميلاد لا يمكن أن تكون في المستقبل.',

            'price.numeric' => 'السعر يجب أن يكون رقمًا.',
            'price.min' => 'السعر لا يمكن أن يكون سالبًا.',

            'gender.in' => 'الجنس يجب أن يكون ذكرًا أو أنثى.',

            'date_of_birth.date' => 'تاريخ الميلاد غير صالح.',

            'height.numeric' => 'الطول يجب أن يكون رقمًا.',
            'height.max' => 'قيمة الطول غير صالحة.',

            'weight.numeric' => 'الوزن يجب أن يكون رقمًا.',
            'weight.max' => 'قيمة الوزن غير صالحة.',

            'goal_id.exists' => 'الهدف المحدد غير موجود.',
            'activity_level_id.exists' => 'مستوى النشاط المحدد غير موجود.',

            'health_condition_ids.array' => 'الحالات الصحية يجب أن تكون قائمة.',
            'health_condition_ids.*.exists' => 'إحدى الحالات الصحية المحددة غير موجودة.',

            'dietary_restriction_ids.array' => 'القيود الغذائية يجب أن تكون قائمة.',
            'dietary_restriction_ids.*.exists' => 'إحدى القيود الغذائية المحددة غير موجودة.',

            'health_condition_note.string' => 'ملاحظات الحالة الصحية يجب أن تكون نصًا.',
            'dietary_restriction_note.string' => 'ملاحظات القيود الغذائية يجب أن تكون نصًا.',

            'training_location_id.exists' => 'مكان التدريب المحدد غير موجود.',

            'available_days.array' => 'أيام التوفر يجب أن تكون قائمة.',
            'available_days.*.string' => 'يوم التوفر يجب أن يكون نصًا.',

            'trainer_type.in' => 'نوع المدرب غير صالح.',

            'disclaimer_accepted.boolean' => 'قيمة الموافقة يجب أن تكون صحيحة أو خاطئة.',

            'profile_photo.image' => 'صورة الملف الشخصي يجب أن تكون صورة.',
            'profile_photo.mimes' => 'صورة الملف الشخصي يجب أن تكون بصيغة JPG أو JPEG أو PNG.',
            'profile_photo.max' => 'حجم صورة الملف الشخصي يجب ألا يتجاوز 2 ميجابايت.',
        ];
    }
}
