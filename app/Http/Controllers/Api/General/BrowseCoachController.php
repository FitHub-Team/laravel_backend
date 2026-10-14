<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrowseCoachRequest;
use App\Http\Resources\CoachResource;
use App\Services\General\BrowseCoach;
use App\Http\Resources\CoachIndexResource;
use App\Http\Resources\CoachSubscriptionResource;
use App\Models\User;
use Illuminate\Http\Request;

class BrowseCoachController extends Controller
{
    public function __construct(
        private BrowseCoach $browseCoach
    ) {}

    public function index(BrowseCoachRequest $request)
    {
        $coaches = $this->browseCoach->getActiveCoaches(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'data' => CoachResource::collection($coaches),
        ]);
    }
}