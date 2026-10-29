<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProgressExercise extends Model
{
    protected $fillable = [
        'trainee_id',
        'workout_exercise_id',
        'workout_session_id',
        'is_completed',
        'notes',
        'sets',
        'repetitions',
        'weight',
        'duration',
        'completed_at',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
        'completed_at' => 'datetime',
        'weight' => 'decimal:2',
    ];

    /**
     * المتدرب صاحب التقدم.
     */
    public function trainee(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'trainee_id'
        );
    }
    public function workoutSession(): \Illuminate\Database\Eloquent\Relations\BelongsTo
{
    return $this->belongsTo(
        workout_session::class,
        'workout_session_id'
    );
}

    /**
     * التمرين الموجود داخل خطة التمارين.
     */
    public function workoutExercise(): BelongsTo
    {
        return $this->belongsTo(
            WorkoutExercise::class,
            'workout_exercise_id'
        );
    }
}