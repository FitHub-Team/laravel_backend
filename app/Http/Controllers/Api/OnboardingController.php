<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompleteOnboardingRequest;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    // User Profile and Data

    function completeProfile(CompleteOnboardingRequest $request)
    {
        $validated = $request->validated();

        $user = $request->user();
        $user->profile()->updateOrCreate(
            [],
            $validated
        );

        return response()->json([
            'message' => 'تم حفظ بيانات الملف الشخصي بنجاح',
            'user' => $user->load('profile')
        ], 200);
    }
    function getProfile(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'user' => $user->load('profile')
        ], 200);
    }
    function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'nullable|in:male,female',
            'age' => 'nullable|integer|min:10|max:100',
            'height' => 'nullable|numeric|min:50|max:250',
            'weight' => 'nullable|numeric|min:20|max:300',
            'health_goal' => 'nullable|in:weight_loss,muscle_building,maintain_fitness',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|array',
            'dietary_preference' => 'nullable|string',
            'disclaimer_accepted' => 'nullable|boolean',
        ]);

        $user = $request->user();
        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'الملف الشخصي للمستخدم غير موجود.'
            ], 404);
        }
        $user->profile()->update(
            $validated
        );

        return response()->json([
            'message' => 'تم حفظ بيانات الملف الشخصي بنجاح',
            'user' => $user->load('profile')
        ], 200);
    }
    public function deleteProfile(Request $request)
    {
        $user = $request->user();

        $profile = $user->profile;

        if (!$profile) {
            return response()->json([
                'message' => 'الملف الشخصي للمستخدم غير موجود.'
            ], 404);
        }

        $profile->delete();

        return response()->json([
            'message' => 'تم حذف بيانات الملف الشخصي بنجاح'
        ], 200);
    }
}
