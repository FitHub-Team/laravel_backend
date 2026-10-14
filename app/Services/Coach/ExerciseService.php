<?php

namespace App\Services\Coach;

use App\Models\Exercise;

class ExerciseService
{
    public function getExercises(array $filters = [])
    {
        return Exercise::query()
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($filters['muscle_group'] ?? null, function ($query, $muscleGroup) {
                $query->where('muscle_group', $muscleGroup);
            })
            ->when($filters['difficulty_level'] ?? null, function ($query, $level) {
                $query->where('difficulty_level', $level);
            })
            ->latest()
            ->paginate(10);
    }
}