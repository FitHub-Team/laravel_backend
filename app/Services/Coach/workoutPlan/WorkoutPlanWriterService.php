<?php

namespace App\Services\Coach\workoutPlan;

use App\Models\User;
use App\Models\WorkoutPlan;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class WorkoutPlanWriterService
{
    /**

     * العثور على أحدث مسودة للمدرب والمتدرب وتحديثها، أو إنشاء مسودة جديدة، مع حساب رقم الإصدار التالي.

     */

    private function getOrCreateDraft(
        int $coachId,
        int $traineeId,
        array $attributes
    ): WorkoutPlan {
        $plan = WorkoutPlan::query()
            ->where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->where('status', 'draft')
            ->orderByDesc('id')
            ->first();

        $lastPublishedVersion = (int) WorkoutPlan::query()
            ->where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->where('status', '!=', 'draft')
            ->max('version');

        $values = array_merge($attributes, [
            'version' => $lastPublishedVersion + 1,
        ]);

        if ($plan) {
            $plan->update($values);

            return $plan;
        }

        return WorkoutPlan::create(array_merge($values, [
            'coach_id' => $coachId,
            'trainee_id' => $traineeId,
            'status' => 'draft',
        ]));
    }

    /**

     * استبدال محتوى المسودة بالكامل: يتحقق من عدم وجود جلسات تدريب مرتبطة، ومن عدم تكرار الأيام وصحة أيام التمارين، ثم يحفظ الأيام والتمارين الجديدة.

     */

   private function replacePlanContent(
    WorkoutPlan $plan,
    array $data
): void {
    if ($plan->status !== 'draft') {
        throw ValidationException::withMessages([
            'status' => ['يمكن تعديل محتوى المسودة فقط.'],
        ]);
    }

    // منع استبدال الأيام المرتبطة بجلسات تدريب.
    $hasSessions = DB::table('workout_sessions')
        ->whereIn(
            'workout_plan_day_id',
            $plan->days()->select('id')
        )
        ->exists();

    if ($hasSessions) {
        throw ValidationException::withMessages([
            'days' => [
                'لا يمكن استبدال أيام خطة مرتبطة بجلسات تدريب.',
            ],
        ]);
    }

    $days = collect($data['days'] ?? []);

    if ($days->isEmpty()) {
        throw ValidationException::withMessages([
            'days' => ['يجب إضافة يوم واحد على الأقل.'],
        ]);
    }

    if ($days->pluck('day')->duplicates()->isNotEmpty()) {
        throw ValidationException::withMessages([
            'days' => ['لا يمكن تكرار نفس اليوم داخل الخطة.'],
        ]);
    }

    // التحقق قبل حذف المحتوى القديم لمنع فقدان التمارين
    // عند إرسال بنية الطلب القديمة.
    foreach ($days as $index => $day) {
        if (
            !array_key_exists('exercises', $day)
            || !is_array($day['exercises'])
        ) {
            throw ValidationException::withMessages([
                "days.{$index}.exercises" => [
                    'يجب إرسال قائمة التمارين داخل كل يوم، ويمكن أن تكون فارغة.',
                ],
            ]);
        }
    }

    // حذف التمارين قبل الأيام بسبب المفتاح الأجنبي.
    $plan->workoutExercises()->delete();
    $plan->days()->delete();

    foreach ($days as $day) {
        $savedDay = $plan->days()->create([
            'day' => $day['day'],
            'date' => $day['date'] ?? null,
            'focus' => $day['focus'] ?? null,
        ]);

        foreach ($day['exercises'] as $exercise) {
            $plan->workoutExercises()->create([
                'workout_plan_day_id' => $savedDay->id,

                'exercise_id' =>
                    $exercise['exercise_id'] ?? null,

                'custom_exercise_id' =>
                    $exercise['custom_exercise_id'] ?? null,

                'sets' => $exercise['sets'],
                'reps' => $exercise['reps'],

                'rest_time' => $exercise['rest_time'] ?? null,
                'notes' => $exercise['notes'] ?? null,

                'exercise_name' =>
                    $exercise['exercise_name'] ?? null,

                'target_muscle' =>
                    $exercise['target_muscle'] ?? null,

                'equipment' =>
                    $exercise['equipment'] ?? null,

                'estimated_duration_seconds' =>
                    $exercise['estimated_duration_seconds'] ?? null,

                'estimated_calories' =>
                    $exercise['estimated_calories'] ?? null,
            ]);
        }
    }

    $plan->unsetRelation('days');
    $plan->unsetRelation('workoutExercises');
}
    private function lockTrainee(int $traineeId): void
    {
        User::query()
            ->whereKey($traineeId)
            ->lockForUpdate()
            ->firstOrFail();
    }

    /**

     * تحويل نتيجة إنشاء خطة التمرين بالذكاء الاصطناعي إلى صيغة النظام، ثم حفظ الأيام والتمارين كمسودة مع تحديد تاريخ البداية والنهاية.

     */

    public function saveAIPlan(
        int $coachId,
        int $traineeId,
        array $aiResult
    ): WorkoutPlan {
        return DB::transaction(function () use (
            $coachId,
            $traineeId,
            $aiResult
        ) {
            $this->lockTrainee($traineeId);

            $startDate = now();

            $durationWeeks = max(
                1,
                (int) ($aiResult['duration_weeks'] ?? 1)
            );

            $plan = $this->getOrCreateDraft(
                $coachId,
                $traineeId,
                [
                    'start_date' => $startDate->toDateString(),
                    'end_date' => $startDate->copy()
                        ->addWeeks($durationWeeks)
                        ->toDateString(),
                ]
            );

            $this->replacePlanContent(
                $plan,
                $this->convertAIResult($aiResult)
            );

            return $plan->load([
                'days',
                'workoutExercises',
            ]);
        });
    }
    /**

     * تحويل بنية نتيجة الذكاء الاصطناعي (الأسابيع والأيام والجلسات والتمارين) إلى مصفوفتَي days وexercises اللتين تقبلهما خدمة حفظ الخطط.

     */

    private function convertAIResult(array $aiResult): array
    {
        $dayNames = [
            1 => 'sunday',
            2 => 'monday',
            3 => 'tuesday',
            4 => 'wednesday',
            5 => 'thursday',
            6 => 'friday',
            7 => 'saturday',
        ];

        $days = [];
        $exercises = [];

        foreach ($aiResult['weeks'] ?? [] as $week) {
            foreach ($week['days'] ?? [] as $day) {
                $dayOfWeek = $dayNames[$day['day_number'] ?? null] ?? null;

                if ($dayOfWeek === null) {
                    continue;
                }

                $days[] = [
                    'day' => $dayOfWeek,
                    'date' => null,
                    'focus' => $day['session']['focus'] ?? null,
                ];

                if (!empty($day['is_rest']) || empty($day['session'])) {
                    continue;
                }

                foreach ($day['session']['exercises'] ?? [] as $exercise) {
                    $exercises[] = [
                        'exercise_id' => null,
                        'custom_exercise_id' => null,

                        'day_of_week' => $dayOfWeek,

                        'sets' => (int) ($exercise['sets'] ?? 0),
                        'reps' => (string) ($exercise['reps'] ?? ''),

                        'rest_time' =>
                        $exercise['rest_seconds'] ?? null,

                        'notes' => null,

                        'exercise_name' =>
                        $exercise['name'] ?? null,

                        'target_muscle' =>
                        $exercise['target_muscle'] ?? null,

                        'equipment' =>
                        $exercise['equipment'] ?? null,

                        // لا نفترض قيمًا غير موجودة في المصدر
                        'estimated_duration_seconds' => null,
                        'estimated_calories' => null,
                    ];
                }
            }
        }

        return [
            'days' => $days,
            'exercises' => $exercises,
        ];
    }
    /**

     * حفظ خطة تمرين أو تحديث المسودة الحالية للمتدرب،
     *  مع التحقق من الحالة وحفظ المحتوى
     *  داخل معاملة قاعدة بيانات. إذا كانت الحالة active يتم تفعيل الخطة.

     */

    public function saveOrUpdatePlan(
        int $coachId,
        int $traineeId,
        array $data
    ): WorkoutPlan {
        // الحالة الافتراضية للخطة هي مسودة ما لم تُرسل حالة أخرى.
        $status = $data['status'] ?? 'draft';

        if (!in_array($status, ['draft', 'active'], true)) {
            throw ValidationException::withMessages([
                'status' => ['حالة الخطة غير صالحة.'],
            ]);
        }

        return DB::transaction(function () use (
            $coachId,
            $traineeId,
            $data,
            $status
        ) {
            // منع تعارض عمليات الحفظ المتزامنة للمتدرب نفسه.
            $this->lockTrainee($traineeId);

            $plan = $this->getOrCreateDraft(
                $coachId,
                $traineeId,
                [
                    'start_date' => $data['start_date'] ?? null,
                    'end_date' => $data['end_date'] ?? null,
                ]
            );

            // استبدال الأيام والتمارين بالمحتوى المرسل.
            $this->replacePlanContent($plan, $data);

            if ($status === 'active') {
                $this->activateDraft($plan);
            }

          return $plan->load([
    'days',
    'workoutExercises.exercise',
    'workoutExercises.customExercise',
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
}
