<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomExercise extends Model
{
    protected $fillable = [
        'coach_id',
        'name',
        'description',
        'muscle_group',
        'equipment',
        'difficulty',
        'instructions',
    ];
    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
       public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class, 'custom_exercise_id');
    }
}
