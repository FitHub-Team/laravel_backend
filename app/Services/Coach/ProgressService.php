<?php

namespace App\Services\Coach;

use App\Models\TraineeProgress;
use Illuminate\Database\Eloquent\Collection;

class ProgressService
{
    public function getTraineeReports(int $coachId, int $trainee_id): Collection
    {
        return TraineeProgress::where('coach_id', $coachId)
            ->where('trainee_id', $trainee_id)
            ->latest('recorded_at')
            ->get();
    }
}