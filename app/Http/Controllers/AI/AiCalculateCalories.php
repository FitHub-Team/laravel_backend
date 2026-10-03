<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Services\AI\AiService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiCalculateCalories extends Controller
{
    public function __construct(
        private AiService $aiService
    ) {}
    public function generateCalculate()
    { {
            $user = Auth::user();

            $profile = $user->userProfile()
                ->with([
                    'goal',
                    'activityLevel',
                    'healthConditions',
                ])
                ->first();

            if (!$profile) {
                return response()->json([
                    'message' => 'بيانات الملف الشخصي للمستخدم غير موجودة.',
                ], 422);
            }

            $data = [
                "age" => Carbon::parse($profile->date_of_birth)->age,
                "weight_kg" => $profile->weight,
                "height_cm" => $profile->height,
                'gender' => $profile->gender,
                'activity_level' => $profile->activityLevel?->code,
                'goal' => $profile->goal?->code,
                // "pace" => "moderate"
            ];

            $result = $this->aiService->generateCalculate($data);

            return response()->json($result);
        }
    }
}
