<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCoachProfileRequest;
use App\Models\User;
use App\Services\Coach\CoachService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingProfileController extends Controller
{
    protected CoachService $coachService;

    public function __construct(CoachService $coachService)
    {
        $this->coachService = $coachService;
    }

    public function update(StoreCoachProfileRequest $request): JsonResponse
    {
        $profile = $this->coachService->saveCoachProfile(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Coach profile updated successfully',
            'data' => $profile
        ], 200);
    }

    public function show(Request $request): JsonResponse
    {
        $profile = $this->coachService->getCoachProfile($request->user()->id);

        if (!$profile) {
            return response()->json(['message' => 'Coach profile not found'], 404);
        }

        return response()->json(['data' => $profile], 200);
    }

    public function showPublicProfile($id)
    {
        $coach = User::with(['coachProfile'])->find($id);

        if (!$coach) {
            return response()->json([
                'message' => 'Coach not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $coach
        ], 200);
    }

    public function showTraineeDetails($id)
    {
        $trainee = User::with(['userProfile'])->find($id);

        if (!$trainee) {
            return response()->json([
                'message' => 'Trainee not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $trainee
        ], 200);
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $coach = $request->user();

            // Store the new profile photo
            $photoPath = $request->file('profile_photo')->store('coach_avatar', 'public');

            // Update the coach's profile photo
            $profile = $this->coachService->updateProfilePhoto($coach, $photoPath);

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث صورة الملف الشخصي بنجاح',
                'data' => [
                      'profile_photo' => asset('storage/' . $photoPath),
                ],
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ اثناء تحديث صورة الملف الشخصي',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}