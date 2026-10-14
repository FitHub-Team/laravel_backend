<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthInformationResource;
use Illuminate\Http\Request;

class HealthInformationController extends Controller
{
    // جلب المعلومات الصحية للمتدرب الحالي
    public function show(Request $request)
    {
        // نفترض أن المعلومات مخزنة في جدول الـ profile أو نموذج المستخدم
        $user = $request->user();
        
        // يمكنك إرجاع الـ user أو الـ profile الخاص به
        return new HealthInformationResource($user->profile ?? $user);
    }

    // تحديث المعلومات الصحية
    public function update(Request $request)
    {
        $user = $request->user();
        $profile = $user->profile ?? $user;

        // التحفظ وتحديث الحقول المعتمدة
        $profile->update($request->only([
            'health_status',
            'chronic_diseases',
            'previous_injuries',
            'current_medications',
            'other_details'
        ]));

        return response()->json([
            'status' => true,
            'message' => 'Health information updated successfully',
            'data' => new HealthInformationResource($profile)
        ]);
    }
}