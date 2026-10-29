<?php

namespace App\Services\Coach\workoutPlan;

use App\Models\User;
use App\Models\WorkoutPlan;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * خدمة إدارة خطط التمرين: الحفظ والتعديل والإرسال والإلغاء والحذف والنسخ وإدارة الإصدارات.
 */
class WorkoutPlanService
{
    /**
     * تهيئة الخدمة وربطها بخدمة الاستعلامات التي تتولى جلب الخطط والإصدارات من قاعدة البيانات.
     */
    public function __construct(
        private WorkoutPlanQueryService $queries
    ) {}
    /**
     * جلب خطة المتدرب التابعة للمدرب المحدد، أو إرجاع null إذا لم توجد خطة.
     */
    public function getPlanByTrainee(
        int $coachId,
        int $traineeId
    ): ?WorkoutPlan {
        return $this->queries->getPlanByTrainee(
            $coachId,
            $traineeId
        );
    }
    /**
     * جلب خطة محددة بواسطة رقمها مع التأكد من أنها تابعة للمدرب المحدد.
     */
    public function getPlanById(
        int $coachId,
        int $planId
    ): WorkoutPlan {
        return $this->queries->getPlanById(
            $coachId,
            $planId
        );
    }

    /**

     * إرجاع قائمة خطط التمرين الخاصة بالمدرب.

     */

    public function getPlansByCoach(int $coachId): Collection
    {
        return $this->queries->getPlansByCoach($coachId);
    }

    /**

     * إرجاع إصدارات خطط التمرين الخاصة بالمدرب.

     */

    public function getVersionsByCoach(int $coachId): Collection
    {
        return $this->queries->getVersionsByCoach($coachId);
    }

   

   

    /**

     * إرسال/تفعيل مسودة موجودة بعد التأكد من ملكية المدرب للخطة، ثم إرجاع الخطة مع الأيام والتمارين.

     */

    public function sendDraftPlan(
        int $coachId,
        int $planId
    ): WorkoutPlan {
        return DB::transaction(function () use ($coachId, $planId) {
            $target = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->findOrFail($planId);

            $this->lockTrainee((int) $target->trainee_id);

            $plan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            $this->activateDraft($plan);

            return $plan->load([
                'days',
                'workoutExercises',
            ]);
        });
    }
     /**

     * التحقق من أن الخطة مسودة وتحتوي على تمارين، ثم تعيين مدتها أسبوعًا وتفعيلها وتحويل الخطة النشطة السابقة للمتدرب إلى مكتملة.

     */

    private function activateDraft(WorkoutPlan $plan): void
    {
        if ($plan->status !== 'draft') {
            throw ValidationException::withMessages([
                'status' => ['يمكن إرسال المسودات فقط.'],
            ]);
        }

        if (!$plan->workoutExercises()->exists()) {
            throw ValidationException::withMessages([
                'exercises' => ['لا يمكن إرسال خطة بدون تمارين.'],
            ]);
        }

        // نفس سلوك مشروعك الحالي: الخطة المرسلة مدتها أسبوع
        $startDate = today('Asia/Hebron');
        $endDate = $startDate->copy()->addDays(6);

        $lastPublishedVersion = (int) WorkoutPlan::query()
            ->where('coach_id', $plan->coach_id)
            ->where('trainee_id', $plan->trainee_id)
            ->where('status', '!=', 'draft')
            ->max('version');

        if ((int) $plan->version <= $lastPublishedVersion) {
            $plan->update([
                'version' => $lastPublishedVersion + 1,
            ]);
        }

        WorkoutPlan::query()
            ->where('coach_id', $plan->coach_id)
            ->where('trainee_id', $plan->trainee_id)
            ->where('status', 'active')
            ->where('id', '!=', $plan->id)
            ->update([
                'status' => 'completed',
            ]);

        $plan->update([
            'status' => 'active',
            'start_date' => $startDate->toDateString(),
            'end_date' => $endDate->toDateString(),
        ]);
    }

    /**

     * قفل سجل المتدرب مؤقتًا داخل معاملة قاعدة البيانات لتقليل تعارض العمليات المتزامنة على خططه.

     */

    private function lockTrainee(int $traineeId): void
    {
        User::query()
            ->whereKey($traineeId)
            ->lockForUpdate()
            ->firstOrFail();
    }

   

 
   

   

    /**

     * حذف مسودة الخطة فقط، مع منع حذفها إذا كانت مرتبطة بجلسات تدريب، وحذف تمارينها وأيامها قبل حذف الخطة.

     */

    public function deleteDraftPlan(
        int $coachId,
        int $planId
    ): void {
        DB::transaction(function () use ($coachId, $planId) {
            $plan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($plan->status !== 'draft') {
                throw ValidationException::withMessages([
                    'status' => ['يمكن حذف المسودات فقط.'],
                ]);
            }

            $hasSessions = DB::table('workout_sessions')
                ->whereIn(
                    'workout_plan_day_id',
                    $plan->days()->select('id')
                )
                ->exists();

            if ($hasSessions) {
                throw ValidationException::withMessages([
                    'days' => [
                        'لا يمكن حذف مسودة مرتبطة بجلسات تدريب.',
                    ],
                ]);
            }

            $plan->workoutExercises()->delete();
            $plan->days()->delete();
            $plan->delete();
        });
    }

    /**

     * إلغاء خطة نشطة فقط بتغيير حالتها إلى cancelled، ثم إرجاع أحدث نسخة من سجل الخطة.

     */

    public function cancelActivePlan(
        int $coachId,
        int $planId
    ): WorkoutPlan {
        return DB::transaction(function () use ($coachId, $planId) {
            $plan = WorkoutPlan::query()
                ->where('coach_id', $coachId)
                ->whereKey($planId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($plan->status !== 'active') {
                throw ValidationException::withMessages([
                    'status' => ['يمكن إلغاء الخطط النشطة فقط.'],
                ]);
            }

            $plan->update([
                'status' => 'cancelled',
            ]);

            return $plan->fresh();
        });
    }

    /**

     * إنشاء مسودة جديدة بنسخ خطة نشطة للمتدرب نفسه، بما يشمل أيامها وتمارينها، مع إصدار جديد ومنع وجود مسودة أخرى مسبقًا.

     */

  
}