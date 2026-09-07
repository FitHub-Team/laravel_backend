<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressExercise extends Model
{
    protected $fillable = [
        'completed',
        'completed_sets',
        'completed_reps',
        'notes',
    ];
    protected $casts = [
        'completed' => 'boolean',
    ];
    public function traineeProgress(): BelongsTo
    {
        return $this->belongsTo(
            TraineeProgress::class,
            'trainee_progress_id'
        );
    }
    public function workoutExercise(): BelongsTo
    {
        return $this->belongsTo(
            WorkoutExercise::class,
            'workout_exercise_id'
        );
    }
}
