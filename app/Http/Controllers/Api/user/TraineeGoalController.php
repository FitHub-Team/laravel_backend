<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\user\TraineeGoalResource;
use Illuminate\Http\Request;

class TraineeGoalController extends Controller
{
    public function show(Request $request)
    {
        $profile = $request->user()->profile()->with(['goal', 'activityLevel'])->first();

        if (!$profile) {
            return response()->json([
                'status' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        return new TraineeGoalResource($profile);
    }

    public function update(Request $request)
    {
        $profile = $request->user()->profile;

        if (!$profile) {
            return response()->json([
                'status' => false,
                'message' => 'Profile not found'
            ], 404);
        }

        $validated = $request->validate([
            'goal_id'           => 'sometimes|exists:goals,id',
            'target_weight'     => 'sometimes|string',
            'goal_duration'     => 'sometimes|string',
            'activity_level_id' => 'sometimes|exists:activity_levels,id',
            'additional_notes'  => 'sometimes|string',
        ]);

      

        // تنفيذ التحديث
        $profile->update($validated);
        
        // إعادة تحميل العلاقات لتحديث البيانات في الـ Resource
        $profile->load(['goal', 'activityLevel']);

        return response()->json([
            'status' => true,
            'message' => 'Goal information updated successfully',
            'data' => new TraineeGoalResource($profile)
        ]);
    }
}