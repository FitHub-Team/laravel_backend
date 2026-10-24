<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\TraineeGoalResource;
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
            'goal_id'           => 'sometimes|nullable|exists:goals,id',
            'target_weight'     => 'sometimes|nullable|string',
            'goal_duration'     => 'sometimes|nullable|string',
            'activity_level_id' => 'sometimes|nullable|exists:activity_levels,id',
            'notes'             => 'sometimes|nullable|string',
            'additional_notes'  => 'sometimes|nullable|string',
        ]);

        if (array_key_exists('notes', $validated)) {
            $validated['additional_notes'] = $validated['notes'];
            unset($validated['notes']);
        }

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