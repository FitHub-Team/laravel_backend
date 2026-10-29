<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class workout_session extends Model
{
    protected $fillable = [
        'trainee_id',
        'workout_plan_day_id',
        'scheduled_date',
        'status',
        'started_at',
        'completed_at',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function workoutPlanDay(): BelongsTo
    {
        return $this->belongsTo(
            WorkoutPlanDay::class,
            'workout_plan_day_id'
        );
    }
    public function progressExercises(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(
            ProgressExercise::class,
            'workout_session_id'
        );
    }
}
