<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Models\TraineeProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
  
    public function getTraineeProgress(Request $request, $trainee_id)
    {
        $reports = TraineeProgress::where('coach_id', $request->user()->id)
            ->where('trainee_id', $trainee_id)
            ->latest('recorded_at')
            ->get();

        return response()->json([
            'status' => true,
            'data' => $reports
        ], 200);
    }
}