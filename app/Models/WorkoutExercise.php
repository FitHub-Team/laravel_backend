<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = ['workout_plan_id', 'day_of_week', 'exercise_name', 'sets', 'reps', 'rest_time', 'notes'];

    public function workoutPlan()
    {
        return $this->belongsTo(WorkoutPlan::class);
    }
}
