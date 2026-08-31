<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionPlan extends Model
{
    use HasFactory;

    protected $fillable = ['coach_id', 'trainee_id', 'title', 'daily_calories', 'notes'];

    public function meals()
    {
        return $this->hasMany(NutritionMeal::class);
    }

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }
}