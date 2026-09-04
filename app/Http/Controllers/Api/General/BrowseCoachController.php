<?php

namespace App\Http\Controllers\Api\General;

use App\Http\Controllers\Controller;
use App\Http\Requests\BrowseCoachRequest;
use App\Services\General\BrowseCoach;
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
            'data' => $coaches,
        ]);
    }
}
