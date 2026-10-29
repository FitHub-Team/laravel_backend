<?php

namespace App\Services\Coach\workoutPlan;

use App\Models\CustomExercise;
use App\Models\Exercise;
use App\Models\WorkoutPlan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class WorkoutPlanFormatter
{
    public function format(
        WorkoutPlan $plan,
        int $coachId
    ): WorkoutPlan {
        $plan->loadMissing([
            'days',
            'workoutExercises',
        ]);

        $workoutExercises = $plan->workoutExercises;

        $libraryExercises = Exercise::query()
            ->whereIn(
                'id',
                $workoutExercises
                    ->pluck('exercise_id')
                    ->filter()
                    ->unique()
                    ->all()
            )
            ->get()
            ->keyBy('id');

        $customExercises = CustomExercise::query()
            ->where('coach_id', $coachId)
            ->whereIn(
                'id',
                $workoutExercises
                    ->pluck('custom_exercise_id')
                    ->filter()
                    ->unique()
                    ->all()
            )
            ->get()
            ->keyBy('id');

        // الربط بمعرّف اليوم بدل اسمه
        $exercisesByDay = $workoutExercises
            ->groupBy('workout_plan_day_id');

        $days = $plan->days->map(function ($day) use (
            $exercisesByDay,
            $libraryExercises,
            $customExercises
        ) {
            $exercises = $exercisesByDay
                ->get($day->id, collect())
                ->map(fn ($item) => $this->formatExercise(
                    $item,
                    $libraryExercises,
                    $customExercises
                ))
                ->filter()
                ->values()
                ->all();

            return [
                'id' => $day->id,
                'day' => $day->day,
                'date' => $day->date?->format('Y-m-d'),
                'focus' => $day->focus,
                'exercises' => $exercises,
            ];
        })->values();

        $plan->setRelation('days', $days);

        return $plan;
    }

    private function formatExercise(
        Model $item,
        Collection $libraryExercises,
        Collection $customExercises
    ): ?array {
        $settings = [
            // هذا هو المعرّف المستخدم لاحقًا لتتبّع الإنجاز
            'workout_exercise_id' => $item->id,
            'workout_plan_day_id' => $item->workout_plan_day_id,

            'sets' => $item->sets,
            'reps' => $item->reps,
            'rest_time' => $item->rest_time,
            'notes' => $item->notes,

            'estimated_duration_seconds' =>
                $item->estimated_duration_seconds,

            'estimated_calories' =>
                $item->estimated_calories,
        ];

        if ($item->exercise_id !== null) {
            $exercise = $libraryExercises->get($item->exercise_id);

            if (!$exercise) {
                return null;
            }

            return array_merge([
                'id' => $exercise->id,
                'exercise_id' => $exercise->id,
                'custom_exercise_id' => null,

                'name' => $exercise->name_ar ?: $exercise->name,
                'description' => $exercise->description_ar ?:$exercise->description,
                'muscle_group' =>$exercise->muscle_group_ar?: $exercise->muscle_group,
                'equipment' => $exercise->equipment_ar ?: $exercise->equipment,
                'difficulty_level' => $exercise->difficulty_level?: $exercise->difficulty_level,

                'mechanics' => $exercise->mechanics,
                'category' => $exercise->type_ar ?:$exercise->category,
                'image' => $exercise->image,
                'force_type' => $exercise->force_type,
            ], $settings, [
                'source' => 'library',
            ]);
        }

        if ($item->custom_exercise_id !== null) {
            $exercise = $customExercises->get(
                $item->custom_exercise_id
            );

            if (!$exercise) {
                return null;
            }

            return array_merge([
                'id' => $exercise->id,
                'exercise_id' => null,
                'custom_exercise_id' => $exercise->id,

                'name' => $exercise->name,
                'description' => $exercise->description,
                'muscle_group' => $exercise->muscle_group,
                'equipment' => $exercise->equipment,
                'difficulty_level' => $exercise->difficulty,

                'mechanics' => null,
                'category' => null,
                'image' => $exercise->image,
                'force_type' => null,
            ], $settings, [
                'source' => 'custom',
            ]);
        }

        // دعم السجلات القديمة المخزّنة مباشرة بتمارين الخطة
        return array_merge([
            'id' => $item->id,
            'exercise_id' => null,
            'custom_exercise_id' => null,

            'name' => $item->exercise_name,
            'description' => null,
            'muscle_group' => $item->target_muscle,
            'equipment' => $item->equipment,
            'difficulty_level' => null,

            'mechanics' => null,
            'category' => null,
            'image' => null,
            'force_type' => null,
        ], $settings, [
            'source' => 'ai',
        ]);
    }
}