<?php

namespace App\Services\Coach;

use App\Models\Subscription;
use App\Models\User;
use Carbon\Carbon;

class SubscriptionService
{
    public function getPendingRequests(int $coachId, ?string $search = null)
    {
        $query = Subscription::where('coach_id', $coachId)
            ->where('status', 'pending')
            ->with(['trainee.userProfile.goal']);

        if ($search) {
            $query->search($search);
        }

        return $query->get();
    }
    // زر قبول الاشتراك 
    public function acceptSubscription(int $subscriptionId, int $coachId): Subscription
    {
        $subscription = Subscription::where('id', $subscriptionId)
            ->where('coach_id', $coachId)
            ->firstOrFail();

        $durationDays = 30; // مدة ثابتة للاشتراك الشهري
        $startDate = Carbon::now();
        $endDate = Carbon::now()->addDays($durationDays);

        $subscription->update([
            'status' => 'accepted',
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        return $subscription;
    }
    // رفض الاشتراك
    public function rejectSubscription(int $subscriptionId, int $coachId, ?string $notes): Subscription
    {
        $subscription = Subscription::where('id', $subscriptionId)
            ->where('coach_id', $coachId)
            ->firstOrFail();

        $subscription->update([
            'status' => 'rejected',
            'notes' => $notes,
        ]);

        return $subscription;
    }
    public function getAcceptedTrainees(int $coachId, ?string $search = null)
    {
        $query = Subscription::where('coach_id', $coachId)
            ->where('status', 'accepted')
            ->with([
                'trainee.userProfile.goal',
                'trainee.workoutPlans' => function ($query) use ($coachId) {
                    $query->where('coach_id', $coachId)
                        ->where('status', 'active')
                        ->with('workoutExercises');
                },
                'trainee.traineeProgresses.progressExercises',
            ]);

        if ($search) {
            $query->search($search);
        }

        $trainees = $query->get();

        return $trainees->map(function ($subscription) {

            $trainee = $subscription->trainee;

            // الخطة النشطة الحالية
            $workoutPlan = $trainee->workoutPlans->first();

            // IDs تمارين الخطة الحالية
            $workoutExerciseIds = $workoutPlan
                ? $workoutPlan->workoutExercises->pluck('id')
                : collect();

            // عدد تمارين الخطة
            $totalExercises = $workoutExerciseIds->count();

            // كل Progress Exercises الخاصة بالمتدرب
            $progressExercises = $trainee->traineeProgresses
                ->flatMap(function ($progress) {
                    return $progress->progressExercises;
                });

            // التمارين المكتملة من الخطة الحالية فقط
            $completedExercises = $progressExercises
                ->whereIn('workout_exercise_id', $workoutExerciseIds)
                ->where('completed', true)
                ->count();

            // نسبة الالتزام
            $commitmentPercentage = $totalExercises > 0
                ? round(($completedExercises / $totalExercises) * 100)
                : 0;

            return [
                'id' => $subscription->id,
                'status' => $subscription->status,

                'trainee' => $trainee,

                'workout_plan' => $workoutPlan,

                'commitment_percentage' => min($commitmentPercentage, 100),
            ];
        });
    }
    public function verifyAndGetTraineeDetails(int $coachId, int $traineeId): ?User
    {
        $hasSubscription = Subscription::where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->exists();

        if (!$hasSubscription) {
            return null;
        }

        return User::where('id', $traineeId)
            ->with('profile')
            ->firstOrFail();
    }
}
