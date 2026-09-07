<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NutritionMeal extends Model
{
    use HasFactory;

    protected $fillable = ['nutrition_plan_id', 'meal_name', 'components', 'calories', 'time_to_eat'];

    public function nutritionPlan()
    {
        return $this->belongsTo(NutritionPlan::class);
    }
}