<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\UpdateProfileRequest;
use App\Services\User\ProfileService;
use App\Helper\ImageHelper;
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

            $profile = $user->userProfile()->with([
                'goal',
                'activityLevel',
                'healthConditions',
                'dietaryRestrictions',
                'trainingLocation',
            ])->first();

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

                    'health_conditions' => $profile?->healthConditions?->pluck('title'),

                    'dietary_restrictions' => $profile?->dietaryRestrictions?->pluck('title'),

                    'training_location' => $profile?->trainingLocation?->title,

                    'available_days' => $profile?->available_days,

                    'trainer_type' => $profile?->trainer_type,

                    'health_condition_note' => $profile?->health_condition_note,

                    'dietary_restriction_note' => $profile?->dietary_restriction_note,

                    'disclaimer_accepted' => $profile?->disclaimer_accepted,

                    'profile_photo' => $profile?->profile_photo,

                ],
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'message' => 'Error fetching user profile data',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    public function update(UpdateProfileRequest $request)
    {
        try {
            $validated = $request->validated();

            $profile = $this->profileService->update(
                $request->user(),
                $validated
            );

            if (!$profile) {
                return response()->json([
                    'status' => false,
                    'message' => 'الملف الشخصي للمستخدم غير موجود.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث بيانات الملف الشخصي بنجاح',
                'data' => $profile,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ اثناء تحديث الملف الشخصي',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        //  dd($request);

        try {
            $profile = $this->profileService->updateProfilePhoto(
                $request->user(),
                $request->file('profile_photo')
            );


            if (!$profile) {
                return response()->json([
                    'status' => false,
                    'message' => 'الملف الشخصي للمستخدم غير موجود.',
                ], 404);
            }

            return response()->json([
                'status' => true,
                'message' => 'تم تحديث صورة الملف الشخصي بنجاح',
                'data' => [
                    'profile_photo' => $profile->userProfile->profile_photo
                        ? asset('storage/' . $profile->userProfile->profile_photo)
                        : null,
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
    public function deleteProfilePhoto(Request $request)
    {
        try {
            $profile = $this->profileService->deleteProfilePhoto(
                $request->user()
            );

            if (!$profile) {
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
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ اثناء حذف صورة الملف الشخصي',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
