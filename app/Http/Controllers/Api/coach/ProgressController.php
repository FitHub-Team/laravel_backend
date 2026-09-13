<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Services\Coach\ProgressService;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    protected ProgressService $progressService;

    public function __construct(ProgressService $progressService)
    {
        $this->progressService = $progressService;
    }
 
    public function show(Request $request, $trainee_id)
    {
        $reports = $this->progressService->getTraineeReports($request->user()->id, $trainee_id);

        return response()->json([
            'status' => true,
            'data' => $reports
        ], 200);
    }
}