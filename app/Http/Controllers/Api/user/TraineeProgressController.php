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

        $records = $this->progressService->getTraineeProgress($traineeId);

        if ($records->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'Progress data fetched successfully',
                'data' => [
                    'current_weight' => 0,
                    'weight_change' => 0,
                    'is_decrease' => true,
                    'chart_data' => [],
                    'recent_logs' => []
                ]
            ]);
        }

        // 1. الوزن الحالي (أحدث قياس)
        $latestRecord = $records->first();
        $currentWeight = (float) $latestRecord->weight;

        // 2. حساب الفارق عن القياس السابق
        $previousRecord = $records->skip(1)->first();
        $weightChange = 0;
        $isDecrease = true;

        if ($previousRecord) {
            $diff = $currentWeight - (float) $previousRecord->weight;
            $weightChange = abs(round($diff, 1));
            $isDecrease = $diff <= 0;
        }

        // 3. نقاط الرسم البياني (Chart)
        $chartData = $records->sortBy('recorded_at')->map(function ($item) {
            return [
                'date' => optional($item->recorded_at)->format('Y-m-d') ?? $item->created_at->format('Y-m-d'),
                'label' => optional($item->recorded_at)->translatedFormat('j M') ?? $item->created_at->translatedFormat('j M'),
                'weight' => (float) $item->weight,
            ];
        })->values();

        // 4. السجلات الأخيرة (Recent Logs)
        $recentLogs = TraineeProgressResource::collection($records);

        return response()->json([
            'status' => true,
            'message' => 'Progress data fetched successfully',
            'data' => [
                'current_weight' => $currentWeight,
                'weight_change' => $weightChange,
                'is_decrease' => $isDecrease,
                'chart_data' => $chartData,
                'recent_logs' => $recentLogs,
            ]
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
    public function weightProgress(Request $request)
{
    try {
        $traineeId = $request->user()->id;
        
       
        $data = $this->progressService->getWeightProgressChart($traineeId);

        return response()->json([
            'status' => true,
            'message' => 'Weight progress fetched successfully',
            'data' => $data,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Error: ' . $e->getMessage(),
        ], 500);
    }
}
}