<?php

namespace App\Http\Controllers\Api\coach\WorkoutPlan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\WorkoutPlanRequest;
use App\Models\WorkoutPlan;
use App\Services\Coach\workoutPlan\WorkoutPlanService;
use Illuminate\Http\Request;

class WorkoutPlanController extends Controller
{
    public function __construct(
        protected WorkoutPlanService $workoutPlanService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Get Current Editable Draft
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Save Draft Or Create Active Plan
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Send Existing Draft Plan
    |--------------------------------------------------------------------------
    */

    public function send(Request $request, int $planId)
    {
        $plan = $this->workoutPlanService->sendDraftPlan(
            $request->user()->id,
            $planId
        );

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال الخطة للمشترك بنجاح',
            'data' => $plan,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Get Current Coach Plans
    |--------------------------------------------------------------------------
    */

    public function getPlansByCoach(Request $request)
    {
        $plans = $this->workoutPlanService->getPlansByCoach(
            $request->user()->id
        );

        return response()->json([
            'status' => true,
            'data' => $plans,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Get All Sent Versions
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Get Specific Plan Version
    |--------------------------------------------------------------------------
    */

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

    // Delete draft
    public function destroyDraft(Request $request, int $planId)
    {
        $this->workoutPlanService->deleteDraftPlan(
            $request->user()->id,
            $planId
        );

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المسودة بنجاح',
        ]);
    }

    // Cancel active plan
    public function cancel(Request $request, int $planId)
    {
        $plan = $this->workoutPlanService->cancelActivePlan(
            $request->user()->id,
            $planId
        );

        return response()->json([
            'status' => true,
            'message' => 'تم إلغاء الخطة بنجاح',
            'data' => $plan,
        ]);
    }

    /**
     * Duplicate an active workout plan.
     */
    public function duplicate(Request $request, int $planId)
    {
        $newPlan = $this->workoutPlanService->duplicatePlan(
            $request->user()->id,
            $planId
        );

        return response()->json([
            'status' => true,
            'message' => 'تم إنشاء نسخة جديدة من الخطة بنجاح',
            'data' => $newPlan,
        ], 201);
    }
}
