<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProfileRequest;
use App\Services\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(
        private ProfileService $profileService
    ) {}

    public function store(StoreProfileRequest $request)
    {
        $profile = $this->profileService->store(
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'status' => true,
            'message' => 'تم حفظ بيانات الملف الشخصي بنجاح',
            'data' => $profile,
        ], 200);
    }

    public function show(Request $request)
    {
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
    }

    public function update(StoreProfileRequest $request)
    {
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
    }

  
}