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
        'title',
        'description',
        'start_date',
        'end_date',
        'status'
    ];

    public function workoutExercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }



    public function exercises()
    {
        return $this->belongsToMany(
            Exercise::class,
            'workout_exercises',
            'workout_plan_id',
            'exercise_id'
        )->withPivot([
            'day_of_week',
            'sets',
            'reps',
            'rest_time',
        ])->withTimestamps();
    }

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }
    public function days()
    {
        return $this->hasMany(WorkoutPlanDay::class);
    }
}
