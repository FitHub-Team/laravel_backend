<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCoachProfileRequest;
use App\Services\CoachService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoachProfileController extends Controller
{
    protected CoachService $coachService;

    public function __construct(CoachService $coachService)
    {
        $this->coachService = $coachService;
    }

    public function store(StoreCoachProfileRequest $request): JsonResponse
    {
        $profile = $this->coachService->saveCoachProfile(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'message' => 'Coach profile saved successfully',
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
}
