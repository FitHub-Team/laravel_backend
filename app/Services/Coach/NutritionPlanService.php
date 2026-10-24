<?php

namespace App\Services\Coach;

use App\Models\NutritionPlan;
use App\Services\Coach\nutritionPlan\NutritionPlanService as CoreNutritionPlanService;
use Illuminate\Support\Collection;

/**
 * Backward-compatible facade for the structured nutrition plan service.
 */
class NutritionPlanService
{
    public function __construct(
        private CoreNutritionPlanService $core
    ) {
    }

    public function saveOrUpdatePlan(int $coachId, int $traineeId, array $data): NutritionPlan
    {
        return $this->core->saveOrUpdatePlan($coachId, $traineeId, $data);
    }

    public function getPlanByTrainee(int $coachId, int $traineeId): ?NutritionPlan
    {
        return $this->core->getPlanByTrainee($coachId, $traineeId);
    }

    public function getPlansByCoach(int $coachId): Collection
    {
        return $this->core->getPlansByCoach($coachId);
    }
}
