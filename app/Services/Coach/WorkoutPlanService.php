<?php

namespace App\Services\Coach;

use App\Models\Exercise;
use App\Models\WorkoutPlan;
use Illuminate\Support\Facades\DB;

class WorkoutPlanService
{
    /**
     * جلب خطة المتدرب للمدرب
     */
    public function getPlanByTrainee(
        int $coachId,
        int $traineeId
    ): ?WorkoutPlan {

        $plan = WorkoutPlan::where('coach_id', $coachId)
            ->where('trainee_id', $traineeId)
            ->with([
                'workoutExercises',
                'days',
            ])
            ->first();

        if (!$plan) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | جلب تمارين SuperFit العادية
        |--------------------------------------------------------------------------
        */

        $exerciseIds = $plan->workoutExercises
            ->whereNotNull('exercise_id')
            ->pluck('exercise_id')
            ->unique()
            ->values();

        $libraryExercises = Exercise::whereIn('id', $exerciseIds)
            ->get()
            ->keyBy('id');

        /*
        |--------------------------------------------------------------------------
        | تجهيز أيام الأسبوع
        |--------------------------------------------------------------------------
        */

        $days = $plan->days->map(function ($day) use (
            $plan,
            $libraryExercises
        ) {

            $exercises = $plan->workoutExercises
                ->filter(function ($workoutExercise) use ($day) {
                    return $workoutExercise->day_of_week === $day->day;
                })
                ->map(function ($workoutExercise) use ($libraryExercises) {

                    /*
                    |--------------------------------------------------------------------------
                    | تمرين من مكتبة SuperFit
                    |--------------------------------------------------------------------------
                    */

                    if ($workoutExercise->exercise_id) {

                        $exercise = $libraryExercises->get(
                            $workoutExercise->exercise_id
                        );

                        if (!$exercise) {
                            return null;
                        }

                        return [
                            'id' => $exercise->id,
                            'exercise_id' => $exercise->id,

                            'name' => $exercise->name,
                            'description' => $exercise->description,
                            'muscle_group' => $exercise->muscle_group,
                            'equipment' => $exercise->equipment,
                            'difficulty_level' => $exercise->difficulty_level,
                            'mechanics' => $exercise->mechanics,
                            'category' => $exercise->category,
                            'image' => $exercise->image,
                            'force_type' => $exercise->force_type,

                            'sets' => $workoutExercise->sets,
                            'reps' => $workoutExercise->reps,
                            'rest_time' => $workoutExercise->rest_time,
                            'notes' => $workoutExercise->notes,

                            'source' => 'library',
                        ];
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | تمرين مولد من AI
                    |--------------------------------------------------------------------------
                    */

                    return [
                        'id' => $workoutExercise->id,
                        'exercise_id' => null,

                        'name' => $workoutExercise->exercise_name,
                        'description' => null,
                        'muscle_group' => $workoutExercise->target_muscle,
                        'equipment' => $workoutExercise->equipment,
                        'difficulty_level' => null,
                        'mechanics' => null,
                        'category' => null,
                        'image' => null,
                        'force_type' => null,

                        'sets' => $workoutExercise->sets,
                        'reps' => $workoutExercise->reps,
                        'rest_time' => $workoutExercise->rest_time,
                        'notes' => $workoutExercise->notes,

                        'source' => 'ai',
                    ];
                })
                ->filter()
                ->values()
                ->toArray();

            return [
                'id' => $day->id,
                'day' => $day->day,
                'date' => $day->date?->format('Y-m-d'),
                'focus' => $day->focus,
                'exercises' => $exercises,
            ];
        })->values();

        /*
        |--------------------------------------------------------------------------
        | استبدال days بالعلاقة المجهزة
        |--------------------------------------------------------------------------
        */

        $plan->unsetRelation('days');
        $plan->setRelation('days', $days);

        return $plan;
    }


    /**
     * حفظ أو تحديث خطة عادية من المدرب
     */
    public function saveOrUpdatePlan(
        int $coachId,
        int $traineeId,
        array $data
    ): WorkoutPlan {

        return DB::transaction(function () use (
            $coachId,
            $traineeId,
            $data
        ) {

            $plan = WorkoutPlan::updateOrCreate(
                [
                    'coach_id' => $coachId,
                    'trainee_id' => $traineeId,
                ],
                [
                    'start_date' => $data['start_date'] ?? null,
                    'end_date' => $data['end_date'] ?? null,
                    'status' => $data['status'] ?? 'draft',
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | حذف الأيام والتمارين القديمة
            |--------------------------------------------------------------------------
            */

            $plan->days()->delete();
            $plan->workoutExercises()->delete();

            /*
            |--------------------------------------------------------------------------
            | حفظ الأيام
            |--------------------------------------------------------------------------
            */

            foreach ($data['days'] ?? [] as $dayData) {

                $plan->days()->create([
                    'day' => $dayData['day'],
                    'date' => $dayData['date'] ?? now()->toDateString(),
                    'focus' => $dayData['focus'] ?? null,
                ]);
            }

            /*
            |--------------------------------------------------------------------------
            | حفظ تمارين المكتبة العادية
            |--------------------------------------------------------------------------
            */

            foreach ($data['exercises'] ?? [] as $exerciseData) {

                $plan->workoutExercises()->create([
                    'exercise_id' => $exerciseData['exercise_id'] ?? null,
                    'day_of_week' => $exerciseData['day_of_week'],
                    'sets' => $exerciseData['sets'],
                    'reps' => $exerciseData['reps'],
                    'rest_time' => $exerciseData['rest_time'] ?? null,
                    'notes' => $exerciseData['notes'] ?? null,

                    'exercise_name' => $exerciseData['exercise_name'] ?? null,
                    'target_muscle' => $exerciseData['target_muscle'] ?? null,
                    'equipment' => $exerciseData['equipment'] ?? null,
                ]);
            }

            return $plan->load([
                'workoutExercises',
            ]);
        });
    }


    /**
     * حفظ خطة مولدة بواسطة AI
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

            /*
            |--------------------------------------------------------------------------
            | إنشاء / تحديث الخطة
            |--------------------------------------------------------------------------
            */

            $plan = WorkoutPlan::updateOrCreate(
                [
                    'coach_id' => $coachId,
                    'trainee_id' => $traineeId,
                ],
                [
                    'start_date' => now()->toDateString(),
                    'end_date' => now()
                        ->addWeeks($aiResult['duration_weeks'] ?? 1)
                        ->toDateString(),

                    'status' => 'draft',
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | حذف النسخة القديمة
            |--------------------------------------------------------------------------
            */

            $plan->days()->delete();
            $plan->workoutExercises()->delete();

            /*
            |--------------------------------------------------------------------------
            | أسماء أيام الأسبوع
            |--------------------------------------------------------------------------
            */

            $dayNames = [
                1 => 'sunday',
                2 => 'monday',
                3 => 'tuesday',
                4 => 'wednesday',
                5 => 'thursday',
                6 => 'friday',
                7 => 'saturday',
            ];

            /*
            |--------------------------------------------------------------------------
            | الأسابيع
            |--------------------------------------------------------------------------
            */

            foreach ($aiResult['weeks'] ?? [] as $week) {

                foreach ($week['days'] ?? [] as $day) {

                    $dayNumber = $day['day_number'] ?? null;

                    if (!$dayNumber || !isset($dayNames[$dayNumber])) {
                        continue;
                    }

                    $dayOfWeek = $dayNames[$dayNumber];

                    /*
                    |--------------------------------------------------------------------------
                    | حفظ اليوم
                    |--------------------------------------------------------------------------
                    */

                    $plan->days()->create([
                        'day' => $dayOfWeek,
                        'date' => null,
                        'focus' => $day['session']['focus'] ?? null,
                    ]);

                    /*
                    |--------------------------------------------------------------------------
                    | Rest Day
                    |--------------------------------------------------------------------------
                    */

                    if (
                        ($day['is_rest'] ?? false) ||
                        empty($day['session'])
                    ) {
                        continue;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | حفظ تمارين AI
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $day['session']['exercises'] ?? []
                        as $aiExercise
                    ) {

                        $plan->workoutExercises()->create([
                            /*
                            |--------------------------------------------------------------------------
                            | لا يوجد exercise_id لأن التمرين من مكتبة AI
                            |--------------------------------------------------------------------------
                            */
                            'exercise_id' => null,

                            'day_of_week' => $dayOfWeek,

                            'sets' => (int) ($aiExercise['sets'] ?? 0),

                            /*
                            |--------------------------------------------------------------------------
                            | نخزن 8-12 كما رجعها الـ AI
                            |--------------------------------------------------------------------------
                            */
                            'reps' => (string) (
                                $aiExercise['reps'] ?? ''
                            ),

                            'rest_time' => $aiExercise['rest_seconds'] ?? null,

                            'notes' => null,

                            /*
                            |--------------------------------------------------------------------------
                            | بيانات تمرين AI
                            |--------------------------------------------------------------------------
                            */
                            'exercise_name' => $aiExercise['name'] ?? null,

                            'target_muscle' => $aiExercise['target_muscle'] ?? null,

                            'equipment' => $aiExercise['equipment'] ?? null,
                        ]);
                    }
                }
            }

            return $plan->load([
                'workoutExercises',
            ]);
        });
    }
}