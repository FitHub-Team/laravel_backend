<?php

namespace App\Services\Coach\workoutPlan;

use App\Models\WorkoutPlan;
use Illuminate\Support\Collection;

/**
 * Read-only queries for the coach's workout plans.
 */
class WorkoutPlanQueryService
{
    public function __construct(
        private WorkoutPlanFormatter $formatter
    ) {}

    /**
     * Load an editable draft, or fall back to the latest active plan.
     */
    public function getPlanByTrainee(int $coachId, int $traineeId): ?WorkoutPlan
    {
        $plan = WorkoutPlan::query()
            ->where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->whereIn('status', ['draft', 'active'])
            ->with('trainee')
            ->orderByRaw("CASE WHEN status = 'draft' THEN 0 ELSE 1 END")
            ->orderByDesc('id')
            ->first();

        return $plan ? $this->formatter->format($plan, $coachId) : null;
    }

    /**
     * Load the exact version selected in the current plans table.
     */
    public function getPlanById(int $coachId, int $planId): WorkoutPlan
    {
        $plan = WorkoutPlan::query()
            ->where('coach_id', $coachId)
            ->findOrFail($planId);

        return $this->formatter->format($plan, $coachId);
    }

    /**
     * Show one current plan per trainee; keep older versions in the database.
     */
    public function getPlansByCoach(int $coachId): Collection
    {
        return WorkoutPlan::query()
            ->where('coach_id', $coachId)
            ->whereIn('status', ['draft', 'active'])
            ->with('trainee')
            ->withCount('workoutExercises')
            ->orderByDesc('version')
            ->orderByDesc('id')
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
            ])
            ->unique('trainee_id')
            ->sortByDesc('updated_at')
            ->values();
    }
    public function getVersionsByCoach(int $coachId): Collection
    {
        return WorkoutPlan::query()
            ->where('coach_id', $coachId)
            ->where('status', 'completed')
            ->with('trainee')
            ->withCount('workoutExercises')


            // Count completed versions for each trainee
            ->withCount([
                'trainee as versions_count' => function ($query) use ($coachId) {
                    $query->whereHas('workoutPlans', function ($plans) use ($coachId) {
                        $plans->where('coach_id', $coachId)
                            ->where('status', 'completed');
                    });
                },
            ])

            ->orderByDesc('version')
            ->orderByDesc('id')
            ->get([
                'id',
                'coach_id',
                'trainee_id',
                'version',
                'status',
                'updated_at',
            ])
            ->unique('trainee_id')
            ->sortByDesc('updated_at')
            ->values();
    }
}
