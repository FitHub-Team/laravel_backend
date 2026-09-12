<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'muscles',
        'goal',
        'type',
        'notes',
        'instruction',
    ];

    protected $casts = [
        'muscles' => 'array',
    ];

    // العلاقة مع جدول الربط WorkoutExercise
    public function workoutExercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }
}