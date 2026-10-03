<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityLevel;
use App\Models\Goal;
use App\Models\skill;
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

    public function index()
    {
        $skills = skill::where('is_active', true)->select('id', 'name')->get();

        return response()->json([
            'status' => true,
            'data' => $skills
        ], 200);
    }

    // تحديث مهارات المدرب الحالي
    public function updateCoachSkills(Request $request)
    {
        $request->validate([
            'skill_ids' => 'required|array',
            'skill_ids.*' => 'exists:skills,id',
        ]);

        $coachProfile = $request->user()->coachProfile; // افترض أن العلاقة معينة في موديل User

        // مزامنة المهارات في الجدول الوسيط coach_skills
        $coachProfile->skills()->sync($request->skill_ids);

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث مهارات المدرب بنجاح',
            'skills' => $coachProfile->skills
        ], 200);
    }

    // جلب قائمة المهارات المفعّلة لتطبيق الموبايل
    public function getSkills()
    {
        $skills = skill::where('is_active', true)->get(['id', 'name']);

        return response()->json([
            'status' => true,
            'message' => 'Skills retrieved successfully',
            'data' => $skills
        ], 200);
    }
}
