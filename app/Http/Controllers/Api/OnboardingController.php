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
            'health_goal' => 'required|in: weight_loss, muscle_building, maintain_weight, improve_endurance',
            'medical_conditions' => 'nullable|string',
            'allergies' => 'nullable|array',
            'dietary_preference' => 'nullable|string',
            'disclaimer_accepted' => 'required|boolean',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);


        $user = $request->user();
        if ($request->hasFile('profile_photo')) {
            $path = $request->file('profile_photo')->store('profile_photos', 'public');
            $validated['profile_photo'] = $path;
        }
        $profile = $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $validated
        );

        return response()->json([
            'message' => 'تم حفظ بيانات الملف الشخصي بنجاح',
            'user' => $user->load('profile')
        ], 200);
    }
}















