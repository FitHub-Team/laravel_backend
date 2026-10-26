<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\UpdateProfileRequest;
use App\Http\Resources\user\TraineeProfileResource;
use App\Services\User\ProfileService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class SettingProfileController extends Controller
{
    public function __construct(
        private ProfileService $profileService
    ) {}

    public function show(Request $request)
    {
        try {
            $user = $request->user();

            $profile = $user->userProfile()
                ->with([
                    'goal',
                    'activityLevel',
                    'healthConditions',
                    'dietaryRestrictions',
                    'trainingLocation',
                ])
                ->first();

            $age = $profile?->date_of_birth
                ? Carbon::parse($profile->date_of_birth)->age
                : null;

            return response()->json([
                'message' => 'User profile data',
                'data' => [
                    'fullname' => $user->full_name,
                    'email' => $user->email,
                    'age' => $age,
                    'gender' => $profile?->gender,
                    'height' => $profile?->height,
                    'weight' => $profile?->weight,

                    'goal' => $profile?->goal?->title,
                    'activity_level' => $profile?->activityLevel?->title,

                    'health_conditions' => $profile?->healthConditions
                        ?->pluck('title'),

                    'dietary_restrictions' => $profile?->dietaryRestrictions
                        ?->pluck('title'),

                    'training_location' => $profile?->trainingLocation?->title,
                    'available_days' => $profile?->available_days,

                    'health_condition_note' => $profile?->health_condition_note,
                    'dietary_restriction_note' => $profile?->dietary_restriction_note,

                    'disclaimer_accepted' => $profile?->disclaimer_accepted,
                    'profile_photo' => $profile?->profile_photo,
                ],
            ]);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء جلب بيانات الملف الشخصي.',
            ], 500);
        }
    }

    public function update(UpdateProfileRequest $request)
    {
        try {
            $validated = $request->validated();

            // معالجة الصورة تتم بدالة منفصلة في السيرفس
            unset($validated['profile_photo']);

            $user = $this->profileService->update(
                $request->user(),
                $validated
            );

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'الملف الشخصي للمستخدم غير موجود.',
                ], 404);
            }

            if ($request->hasFile('profile_photo')) {
                $result = $this->profileService->updateProfilePhoto(
                    $user,
                    $request->file('profile_photo')
                );

                if (!$result) {
                    return response()->json([
                        'status' => false,
                        'message' => 'تعذر العثور على الملف الشخصي لتحديث الصورة.',
                    ], 404);
                }
            }

            // تحميل أحدث بيانات المستخدم والبروفايل فقط
            $user->refresh();
            $user->load('userProfile');

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث بيانات الملف الشخصي بنجاح',
                'data' => new TraineeProfileResource($user),
            ], 200);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'status' => false,
                'message' => $e->getMessage() ,
            ], 500);
        }
    }

    public function deleteProfilePhoto(Request $request)
    {
        try {
            $result = $this->profileService->deleteProfilePhoto(
                $request->user()
            );

            if (!$result) {
                return response()->json([
                    'status' => false,
                    'message' => 'الملف الشخصي للمستخدم غير موجود.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'تم حذف صورة الملف الشخصي بنجاح',
            ], 200);
        } catch (Exception $e) {
            report($e);

            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ أثناء حذف صورة الملف الشخصي.',
            ], 500);
        }
    }
}