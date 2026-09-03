<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrowseCoachRequest;
use App\Services\General\BrowseCoach;


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
            'data' => $coaches,
        ]);
    }
    public function show($id)
    {
        $coach = $this->browseCoach->getCoachById($id);

        if (!$coach) {
            return response()->json([
                'success' => false,
                'message' => 'Coach not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $coach,
        ]);
    }
}
