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
        'exercise_id',
        'custom_exercise_id',
        'day_of_week',
        'sets',
        'reps',
        'rest_time',
        'notes',
        'exercise_name',
        'target_muscle',
        'equipment',
    ];

    public function workoutPlan()
    {
        return $this->belongsTo(WorkoutPlan::class);
    }

    // العلاقة مع التمرين الأساسي
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
    public function customExercise(): BelongsTo
    {
        return $this->belongsTo(CustomExercise::class, 'custom_exercise_id');
    }
}
