<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_plan_id',
        'workout_plan_day_id',
        'exercise_id',
        'custom_exercise_id',
        'sets',
        'reps',
        'rest_time',
        'notes',
        'exercise_name',
        'target_muscle',
        'equipment',
        'estimated_duration_seconds',
        'estimated_calories',
    ];
    protected $casts = [
        'estimated_duration_seconds' => 'integer',
        'estimated_calories' => 'decimal:2',
    ];

    public function workoutPlan()
    {
        return $this->belongsTo(WorkoutPlan::class);
    }

    // العلاقة مع التمرين الأساسي
    public function exercise()
    {
        return $this->hasMany(
            WorkoutExercise::class,
            'workout_plan_day_id'
        );
    }
    public function customExercise(): BelongsTo
    {
        return $this->belongsTo(CustomExercise::class, 'custom_exercise_id');
    }
    public function workoutPlanDay(): BelongsTo
    {
        return $this->belongsTo(
            WorkoutPlanDay::class,
            'workout_plan_day_id'
        );
    }
}
