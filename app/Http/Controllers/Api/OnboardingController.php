<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    // User Profile and Data


    function completeProfile(Request $request)
    {
        $validated = $request->validate([
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:10|max:100',
            'height' => 'required|numeric|min:50|max:250',
            'weight' => 'required|numeric|min:20|max:300',
            'health_goal' => 'required|in:weight_loss,muscle_building,maintain_fitness',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|array',
            'dietary_preference' => 'nullable|string',
            'disclaimer_accepted' => 'required|boolean',
        ]);

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
}















