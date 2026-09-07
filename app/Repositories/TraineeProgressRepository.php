<?php

namespace App\Repositories;

use App\Models\TraineeProgress;
use App\Repositories\Contracts\TraineeProgressRepositoryInterface;

class TraineeProgressRepository implements TraineeProgressRepositoryInterface
{
    public function createProgress(array $data)
    {
        return TraineeProgress::create($data);
    }

    public function getTraineeProgress(int $traineeId)
    {
        return TraineeProgress::with('exercises.workoutExercise')
            ->where('trainee_id', $traineeId)
            ->latest('recorded_at')
            ->get();
    }

    public function updateProgress(int $progressId, array $data)
    {
        $progress = TraineeProgress::findOrFail($progressId);

        $progress->update($data);

        return $progress->load('exercises.workoutExercise');
    }

    public function updateProgressPhoto(int $progressId, string $photoPath)
    {
        $progress = TraineeProgress::findOrFail($progressId);

        $progress->update(['progress_photo' => $photoPath]);

        return $progress;
    }
}