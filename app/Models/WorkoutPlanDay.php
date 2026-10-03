<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutPlanDay extends Model
{
    protected $fillable = [
        'workout_plan_id',
        'date',
        'day',
    ];

    protected $casts = [
        'date' => 'date',
    ];
    public function workoutPlan(): BelongsTo
    {
        return $this->belongsTo(WorkoutPlan::class);
    }
}
