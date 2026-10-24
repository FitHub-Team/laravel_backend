<?php

namespace App\Services\Coach\nutritionPlan;

use App\Models\NutritionPlan;
use Illuminate\Support\Collection;

/**
 * Read-only queries for the coach's nutrition plans.
 */
class NutritionPlanQueryService
{
    /**
     * Load an editable draft, or fall back to the latest active plan.
     */
    public function getPlanByTrainee(int $coachId, int $traineeId): ?NutritionPlan
    {
        return NutritionPlan::query()
            ->where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->whereIn('status', ['draft', 'active'])
            ->with(['meals.recipe'])
            ->orderByRaw("CASE WHEN status = 'draft' THEN 0 ELSE 1 END")
            ->orderByDesc('id')
            ->first();
    }

    /**
     * Load a specific plan by id for this coach.
     */
    public function getPlanById(int $coachId, int $planId): NutritionPlan
    {
        return NutritionPlan::query()
            ->where('coach_id', $coachId)
            ->with(['meals.recipe', 'trainee'])
            ->findOrFail($planId);
    }

    /**
     * Show one current plan per trainee; keep older versions in the database.
     */
    public function getPlansByCoach(int $coachId): Collection
    {
        return NutritionPlan::query()
            ->where('coach_id', $coachId)
            ->whereIn('status', ['draft', 'active'])
            ->with('trainee')
            ->withCount('meals')
            ->orderByDesc('version')
            ->orderByDesc('id')
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
            ])
            ->unique('trainee_id')
            ->sortByDesc('updated_at')
            ->values();
    }
}
