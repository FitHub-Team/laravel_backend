<?php

namespace App\Http\Controllers\Api\user;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProgressRequest;
use App\Http\Resources\TraineeProgressResource; 
use App\Services\User\TraineeProgress;

use Exception;
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

            $progress = $this->progressService->createProgress($data);

            return response()->json([
                'status' => true,
                'message' => 'Progress recorded successfully',
                'data' => new TraineeProgressResource($progress), // تمرير السجل المفرد عبر الـ Resource
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error recording progress: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        try {
            $traineeId = $request->user()->id;

            $progress = $this->progressService->getTraineeProgress($traineeId);

            return response()->json([
                'status' => true,
                'message' => 'Progress data fetched successfully',
                'data' => TraineeProgressResource::collection($progress),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error fetching progress data: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(StoreProgressRequest $request, $progressId)
    {
        try {
            $traineeId = $request->user()->id;

            $data = $request->validated();
            if ($request->hasFile('progress_photo')) {
                $data['progress_photo'] = $request->file('progress_photo');
            }

            $progress = $this->progressService->updateProgress($progressId, $data);

            return response()->json([
                'status' => true,
                'message' => 'Progress updated successfully',
                'data' => new TraineeProgressResource($progress), 
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error updating progress: ' . $e->getMessage(),
            ], 500);
        }
    }
}