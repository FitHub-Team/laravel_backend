<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'trainee_id',
        'start_date',
        'end_date',
        'status',
        'version',
    ];

    protected $casts = [
        'version' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function workoutExercises()
    {
        return $this->hasMany(
            WorkoutExercise::class,
            'workout_plan_id'
        );
    }

    public function exercises()
    {
        return $this->belongsToMany(
            Exercise::class,
            'workout_exercises',
            'workout_plan_id',
            'exercise_id'
        )->withPivot([
            'id',
            'workout_plan_day_id',
            'sets',
            'reps',
            'rest_time',
            'notes',
            'estimated_duration_seconds',
            'estimated_calories',
        ])->withTimestamps();
    }

    public function trainee()
    {
        return $this->belongsTo(
            User::class,
            'trainee_id'
        );
    }

    public function days()
    {
        return $this->hasMany(
            WorkoutPlanDay::class,
            'workout_plan_id'
        );
    }
}