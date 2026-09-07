<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkoutPlan extends Model
{
    use HasFactory;

    protected $fillable = 
    ['coach_id', 'trainee_id', 'title', 'description', 'start_date', 'end_date'
    ,'status'];

    public function exercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }
    
    
}