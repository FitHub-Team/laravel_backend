<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MedicalRestrictionController extends Controller
{
    
    public function acceptDisclaimer(Request $request)
    {
        $user = $request->user();

        $profile = $user->profile()->firstOrCreate([
            'user_id' => $user->id
        ]);

        $profile->update([
            'disclaimer_accepted' => true,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Medical disclaimer accepted successfully.',
            'data'    => [
                'disclaimer_accepted' => $profile->disclaimer_accepted
            ]
        ], 200);
    }


    public function updateRestrictions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medical_conditions' => 'nullable|string',
            'allergies'          => 'nullable|array',
            'allergies.*'        => 'string',
            'dietary_preference' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $profile = $user->profile()->firstOrCreate([
            'user_id' => $user->id
        ]);

        $profile->update([
            'medical_conditions' => $request->medical_conditions ?? $profile->medical_conditions,
            'allergies'          => $request->allergies ?? $profile->allergies,
            'dietary_preference' => $request->dietary_preference ?? $profile->dietary_preference,
        ]);

        return response()->json([
            'status'  => true,
            'message' => 'Medical restrictions updated successfully.',
            'profile' => $profile
        ], 200);
    }

   
    public function getRestrictions(Request $request)
    {
        $profile = $request->user()->profile;

        return response()->json([
            'status' => true,
            'data'   => [
                'disclaimer_accepted' => $profile->disclaimer_accepted ?? false,
                'medical_conditions'  => $profile->medical_conditions ?? null,
                'allergies'           => $profile->allergies ?? [],
                'dietary_preference'  => $profile->dietary_preference ?? null,
            ]
        ], 200);
    }
}