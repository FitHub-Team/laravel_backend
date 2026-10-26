<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthInformationResource;
use Illuminate\Http\Request;

class HealthInformationController extends Controller
{
    public function show(Request $request)
    {
        $profile = $request->user()->userProfile;

        abort_if(!$profile, 404, 'Profile not found');

        return new HealthInformationResource($profile);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'health_status' => [
                'sometimes', 'nullable', 'string', 'max:100',
            ],

            'chronic_diseases' => [
                'sometimes', 'nullable', 'array', 'max:50',
            ],
            'chronic_diseases.*' => [
                'required', 'string', 'max:100', 'distinct',
            ],

            'previous_injuries' => [
                'sometimes', 'nullable', 'array', 'max:50',
            ],
            'previous_injuries.*' => [
                'required', 'string', 'max:200', 'distinct',
            ],

            'current_medications' => [
                'sometimes', 'nullable', 'array', 'max:50',
            ],
            'current_medications.*' => [
                'required', 'string', 'max:200', 'distinct',
            ],

            'health_condition_note' => [
                'sometimes', 'nullable', 'string', 'max:5000',
            ],
        ]);

        $profile = $request->user()->userProfile;

        abort_if(!$profile, 404, 'Profile not found');

    

      $profile->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث المعلومات الصحية بنجاح',
            'data' => new HealthInformationResource($profile->refresh()),
        ]);
    }
}