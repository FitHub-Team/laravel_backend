<?php

namespace App\Services\User;

use App\Models\ProgressExercise;
use App\Repositories\Contracts\TraineeProgressRepositoryInterface;
use Illuminate\Support\Facades\DB;

class TraineeProgress
{
    public function __construct(
        private TraineeProgressRepositoryInterface $traineeProgressRepository
    ) {}
    public function createProgress(array $data)
    {
        return DB::transaction(function () use ($data) {

            $exercises = $data['exercises'] ?? [];

            unset($data['exercises']);

            $progress = $this->traineeProgressRepository
                ->createProgress($data);

            foreach ($exercises as $exercise) {
                $progress->exercises()->create($exercise);
            }

            return $progress->load('exercises.workoutExercise');
        });
    }
    public function getTraineeProgress(int $traineeId)
    {
        return $this->traineeProgressRepository
            ->getTraineeProgress($traineeId);
    }
    public function updateProgress(int $progressId, array $data)
    {
        $exercises = $data['exercises'] ?? [];

        unset($data['exercises']);

        return DB::transaction(function () use (
            $progressId,
            $data,
            $exercises
        ) {
            $progress = $this->traineeProgressRepository
                ->updateProgress($progressId, $data);

            foreach ($exercises as $exercise) {
                ProgressExercise::updateOrCreate(
                    [
                        'trainee_progress_id' => $progress->id,
                        'workout_exercise_id' => $exercise['workout_exercise_id'],
                    ],
                    [
                        'completed' => $exercise['completed'] ?? false,
                        'completed_sets' => $exercise['completed_sets'] ?? null,
                        'completed_reps' => $exercise['completed_reps'] ?? null,
                        'notes' => $exercise['notes'] ?? null,
                    ]
                );
            }

            return $progress->load('exercises.workoutExercise');
        });
    }
    public function updateProgressPhoto(int $progressId, string $photoPath)
    {
        return $this->traineeProgressRepository
            ->updateProgressPhoto($progressId, $photoPath);
    }
}
