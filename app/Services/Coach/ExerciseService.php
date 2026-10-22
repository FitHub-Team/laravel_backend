<?php

namespace App\Services\Coach;

use App\Models\Exercise;

class ExerciseService
{
    public function getExercises(array $filters = [])
    {
        return Exercise::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name_ar', 'like', "%{$search}%");
            })
            ->when($filters['muscle_group_ar'] ?? null, function ($query, $muscleGroup) {
                $query->where('muscle_group_ar', $muscleGroup);
            })
            ->when($filters['difficulty_level_ar'] ?? null, function ($query, $level) {
                $query->where('difficulty_level_ar', $level);
            })
            ->latest()
            ->paginate(10);
    }
}