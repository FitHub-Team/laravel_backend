<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutExercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'workout_plan_id', 
        'exercise_id',
        'day_of_week', 
        'sets', 
        'reps', 
        'rest_time', 
    
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
}