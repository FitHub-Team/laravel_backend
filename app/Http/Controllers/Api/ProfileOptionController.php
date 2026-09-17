<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLevel;
use App\Models\Goal;
use Illuminate\Http\Request;

class ProfileOptionController extends Controller
{
    // جلب قائمة الأهداف المفعّلة لتطبيق الموبايل
    public function getGoals()
    {
        $goals = Goal::where('is_active', true)->get()->map(function ($goal) {
            return [
                'id' => $goal->id,
                'title' => $goal->title,
                'description' => $goal->description,
                'image_url' => $goal->image ? asset('storage/' . $goal->image) : null,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Goals retrieved successfully',
            'data' => $goals
        ], 200);
    }

    // جلب مستويات النشاط المفعّلة لتطبيق الموبايل
    public function getActivityLevels()
    {
        $levels = ActivityLevel::where('is_active', true)->get(['id', 'title']);

        return response()->json([
            'status' => true,
            'message' => 'Activity levels retrieved successfully',
            'data' => $levels
        ], 200);
    }
}
