<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\user\UpdateProfileRequest;
use App\Services\User\ProfileService;
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
            $profile = $this->profileService->show(
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
                'message' => 'تم جلب بيانات الملف الشخصي بنجاح',
                'data' => $profile,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'حدث خطأ اثناء جلب الملف الشخصي',
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
}
