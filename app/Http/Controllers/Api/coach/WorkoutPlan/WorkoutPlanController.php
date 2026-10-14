<?php

namespace App\Http\Controllers\Api\coach\WorkoutPlan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\WorkoutPlanRequest;
use App\Models\WorkoutPlan;
use App\Services\Coach\WorkoutPlanService;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    public function __construct(
        protected WorkoutPlanService $workoutPlanService
    ) {}

    // Current editable draft
    public function index(Request $request, $traineeId)
    {
        $plan = $this->workoutPlanService->getPlanByTrainee(
            $request->user()->id,
            $traineeId
        );

        return response()->json([
            'status' => true,
            'data' => $plan,
        ]);
    }

    // Save draft or send a new version
    public function storeOrUpdate(
        WorkoutPlanRequest $request,
        $traineeId
    ) {
        $plan = $this->workoutPlanService->saveOrUpdatePlan(
            $request->user()->id,
            $traineeId,
            $request->validated()
        );

        return response()->json([
            'status' => true,
            'message' => 'Workout plan saved successfully',
            'data' => $plan,
        ]);
    }

    // All sent versions for one trainee
    public function versions(Request $request, $traineeId)
    {
        $plans = WorkoutPlan::query()
            ->where('coach_id', $request->user()->id)
            ->where('trainee_id', $traineeId)
            ->where('status', '!=', 'draft')
            ->withCount('workoutExercises')
            ->orderByDesc('version')
            ->get([
                'id',
                'coach_id',
                'trainee_id',
                'version',
                'status',
                'start_date',
                'end_date',
                'created_at',
                'updated_at',
            ]);

        return response()->json([
            'status' => true,
            'count' => $plans->count(),
            'data' => $plans,
        ]);
    }

    // Details of a specific version
    public function showVersion(
        Request $request,
        $traineeId,
        $planId
    ) {
        $plan = WorkoutPlan::query()
            ->where('coach_id', $request->user()->id)
            ->where('trainee_id', $traineeId)
            ->where('status', '!=', 'draft')
            ->whereKey($planId)
            ->with([
                'days',
                'workoutExercises.exercise',
                'workoutExercises.customExercise',
            ])
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $plan,
        ]);
    }
}
