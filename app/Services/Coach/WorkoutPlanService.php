<?php

namespace App\Services\Coach;

use App\Models\WorkoutPlan;
use Illuminate\Support\Facades\DB;

class WorkoutPlanService
{
    public function saveOrUpdatePlan(int $coachId, int $traineeId, array $data): WorkoutPlan
    {
        return DB::transaction(function () use ($coachId, $traineeId, $data) {
       
            $plan = WorkoutPlan::updateOrCreate(
                [
                    'coach_id' => $coachId,
                    'trainee_id' => $traineeId,
                ],
                [
                    'title' => $data['title'],
                    'description' => $data['description'] ?? null,
                    'start_date' => $data['start_date'] ?? null,
                    'end_date' => $data['end_date'] ?? null,
                ]
            );

           
            $plan->exercises()->delete();
            
            foreach ($data['exercises'] as $exerciseData) {
                $plan->exercises()->create($exerciseData);
            }

            return $plan->load('exercises');
        });
    }

    public function getPlanByTrainee(int $coachId, int $traineeId): ?WorkoutPlan
    {
        return WorkoutPlan::where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->with('exercises')
            ->first();
    }
}