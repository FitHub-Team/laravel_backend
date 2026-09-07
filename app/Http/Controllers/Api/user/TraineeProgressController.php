<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgressRequest;
use App\Services\User\TraineeProgress;
use Illuminate\Http\Request;

class TraineeProgressController extends Controller
{
    public function __construct(
        private TraineeProgress $progressService
    ) {}
    public function store(StoreProgressRequest $request)
    {
        try {
            $traineeId = $request->user()->id;

            $data = $request->validated();

            $data['trainee_id'] = $traineeId;

            $progress = $this->progressService
                ->createProgress($data);

            return response()->json([
                'status' => true,
                'message' => 'Progress recorded successfully',
                'data' => $progress,
            ], 201);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Error recording progress',
            ], 500);
        }
    }
    public function index(Request $request)
    {
        try {
            $traineeId = $request->user()->id;

            $progress = $this->progressService
                ->getTraineeProgress($traineeId);

            return response()->json([
                'status' => true,
                'message' => 'Progress data fetched successfully',
                'data' => $progress,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Error fetching progress data',
            ], 500);
        }
    } 

    public function update(StoreProgressRequest $request, $progressId)
    {
        try {
            $traineeId = $request->user()->id;

            $data = $request->validated();

            $progress = $this->progressService
                ->updateProgress($progressId, $data);

            return response()->json([
                'status' => true,
                'message' => 'Progress updated successfully',
                'data' => $progress,
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'status' => false,
                'message' => 'Error updating progress',
            ], 500);
        }
    }
}
