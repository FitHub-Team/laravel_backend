<?php

namespace App\Services\AI;

use App\Models\Exercise;
use Illuminate\Validation\ValidationException;

class WorkoutExerciseResolver
{
    public function resolve(array $plan): array
    {
        // 1. Collect exercise IDs from all weeks and days
        $exerciseIds = collect($plan['weeks'] ?? [])
            ->flatMap(function ($week) {
                return $week['days'] ?? [];
            })
            ->flatMap(function ($day) {
                if ($day['is_rest'] ?? false) {
                    return [];
                }

                return $day['session']['exercises'] ?? [];
            })
            ->pluck('exercise_id')
            ->unique()
            ->values();

        // No exercises in the generated plan
        if ($exerciseIds->isEmpty()) {
            return $plan;
        }

        // 2. Validate IDs
        $invalidIds = $exerciseIds->filter(function ($id) {
            return filter_var(
                $id,
                FILTER_VALIDATE_INT,
                ['options' => ['min_range' => 1]]
            ) === false;
        });

        if ($invalidIds->isNotEmpty()) {
            throw ValidationException::withMessages([
                'exercises' => [
                    'AI returned invalid exercise IDs: ' .
                    $invalidIds->implode(', ')
                ],
            ]);
        }

        // 3. Fetch all exercises in one query
        $exercises = Exercise::query()
            ->whereIn('id', $exerciseIds->all())
            ->get()
            ->keyBy('id');

        // 4. Check missing exercises
        $missingIds = $exerciseIds->filter(function ($id) use ($exercises) {
            return !$exercises->has((int) $id);
        });

        if ($missingIds->isNotEmpty()) {
            throw ValidationException::withMessages([
                'exercises' => [
                    'Exercises not found: ' .
                    $missingIds->implode(', ')
                ],
            ]);
        }

        // 5. Attach exercise information to AI response
        foreach ($plan['weeks'] as &$week) {
            foreach ($week['days'] as &$day) {

                // Rest day
                if ($day['is_rest'] ?? false) {
                    $day['session'] = null;
                    continue;
                }

                if (!isset($day['session']['exercises'])) {
                    continue;
                }

                foreach ($day['session']['exercises'] as &$item) {
                    $exercise = $exercises->get(
                        (int) $item['exercise_id']
                    );

                    $item['exercise'] = $exercise->toArray();
                }

                unset($item);
            }

            unset($day);
        }

        unset($week);

        return $plan;
    }
}
