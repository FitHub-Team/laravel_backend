<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserDetailResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $profile = $this->userProfile;

        return [

            // ==========================================
            // بيانات المستخدم
            // ==========================================

            'id' => $this->id,

            'full_name' => $this->full_name,

            'email' => $this->email,


            // ==========================================
            // بيانات الملف الشخصي
            // ==========================================

            'gender' =>
                $profile?->gender ?? 'غير محدد',

            'dob' =>
                $profile?->date_of_birth,

            'height' =>
                $profile?->height,

            'weight' =>
                $profile?->weight,

            'health_goal' =>
                $profile?->health_goal,


            // ==========================================
            // صورة المستخدم
            // ==========================================

            'profile_image' =>
                $this->profile_image
                ? asset(
                    'storage/' .
                    $this->profile_image
                )
                : null,
        ];
    }
}
