<?php

namespace App\Services\User;

use App\Helper\ImageHelper;
use App\Models\ProgressExercise;
use App\Models\Subscription;
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

            // جلب المدرب المرتبط بالمتدرب تلقائياً من خلال اشتراكه المقبول
            $traineeId = $data['trainee_id'] ?? null;
            
            if ($traineeId) {
                $subscription = Subscription::where('trainee_id', $traineeId)
                    ->where('status', 'accepted') //أو الحالة المعتمدة 
                    ->first();

                if ($subscription) {
                    $data['coach_id'] = $subscription->coach_id;
                } else {
                    throw new \Exception('لا يوجد مدرب نشط مرتبط بهذا المتدرب حالياً.');
                }
            }

            $exercises = $data['exercises'] ?? [];
            unset($data['exercises']);

            $progress = $this->traineeProgressRepository->createProgress($data);

            foreach ($exercises as $exercise) {
                $progress->exercises()->create($exercise);
            }

            // إرجاع كائن التقدم مباشرة بدون تحميل علاقة التمارين لتجنب أخطاء الأعمدة غير الموجودة
            return $progress;
        });
    }

    public function getTraineeProgress(int $traineeId)
    {
        return $this->traineeProgressRepository->getTraineeProgress($traineeId);
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
            // Get current progress
            $progress = $this->traineeProgressRepository->findProgress($progressId);

            // Handle progress photo
            $image = $data['progress_photo'] ?? null;
            unset($data['progress_photo']);

            if ($image) {
                $data['progress_photo'] = ImageHelper::update(
                    $image,
                    $progress->progress_photo,
                    'progress'
                );
            }

            // Update progress
            $progress = $this->traineeProgressRepository->updateProgress($progressId, $data);

            // Update exercises
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
}