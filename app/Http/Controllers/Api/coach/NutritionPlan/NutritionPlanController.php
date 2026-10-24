<?php

namespace App\Http\Controllers\Api\coach\NutritionPlan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Coach\NutritionPlanRequest;
use App\Models\NutritionPlan;
use App\Services\Coach\nutritionPlan\NutritionPlanService;
use Illuminate\Http\Request;

class NutritionPlanController extends Controller
{
    public function __construct(
        protected NutritionPlanService $nutritionPlanService
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Get Current Editable Draft
    |--------------------------------------------------------------------------
    */
    public function index(Request $request, $traineeId)
    {
        $plan = $this->nutritionPlanService->getPlanByTrainee(
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
    public function storeOrUpdate(NutritionPlanRequest $request, $traineeId)
    {
        $plan = $this->nutritionPlanService->saveOrUpdatePlan(
            $request->user()->id,
            $traineeId,
            $request->validated()
        );

        return response()->json([
            'status' => true,
            'message' => 'Nutrition plan saved successfully',
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
        $plan = $this->nutritionPlanService->sendDraftPlan(
            $request->user()->id,
            $planId
        );

        return response()->json([
            'status' => true,
            'message' => 'تم إرسال خطة التغذية للمشترك بنجاح',
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
        $plans = $this->nutritionPlanService->getPlansByCoach(
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
        $plans = NutritionPlan::query()
            ->where('coach_id', $request->user()->id)
            ->where('trainee_id', $traineeId)
            ->where('status', '!=', 'draft')
            ->withCount('meals')
            ->orderByDesc('version')
            ->get([
                'id',
                'coach_id',
                'trainee_id',
                'title',
                'daily_calories',
                'protein',
                'carbs',
                'fat',
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
    public function showVersion(Request $request, $traineeId, $planId)
    {
        $plan = NutritionPlan::query()
            ->where('coach_id', $request->user()->id)
            ->where('trainee_id', $traineeId)
            ->where('status', '!=', 'draft')
            ->whereKey($planId)
            ->with(['meals.recipe'])
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'data' => $plan,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Delete Draft
    |--------------------------------------------------------------------------
    */
    public function destroyDraft(Request $request, int $planId)
    {
        $this->nutritionPlanService->deleteDraftPlan(
            $request->user()->id,
            $planId
        );

        return response()->json([
            'status' => true,
            'message' => 'تم حذف المسودة بنجاح',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Cancel Active Plan
    |--------------------------------------------------------------------------
    */
    public function cancel(Request $request, int $planId)
    {
        $plan = $this->nutritionPlanService->cancelActivePlan(
            $request->user()->id,
            $planId
        );

        return response()->json([
            'status' => true,
            'message' => 'تم إلغاء خطة التغذية',
            'data' => $plan,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Duplicate Active Plan Into New Draft
    |--------------------------------------------------------------------------
    */
    public function duplicate(Request $request, int $planId)
    {
        $plan = $this->nutritionPlanService->duplicatePlan(
            $request->user()->id,
            $planId
        );

        return response()->json([
            'status' => true,
            'message' => 'تم إنشاء مسودة جديدة من الخطة النشطة',
            'data' => $plan,
        ]);
    }
}
