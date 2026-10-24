<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NutritionMeal extends Model
{
    use HasFactory;

    protected $fillable = [
        'nutrition_plan_id',
        'meal_name',
        'components',
        'calories',
        'protein',
        'carbs',
        'fat',
        'time_to_eat',
        'recipe_id',
        'sort_order',
    ];

    protected $casts = [
        'calories' => 'integer',
        'protein' => 'integer',
        'carbs' => 'integer',
        'fat' => 'integer',
        'sort_order' => 'integer',
    ];

    public function nutritionPlan(): BelongsTo
    {
        return $this->belongsTo(NutritionPlan::class);
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function progressMeals(): HasMany
    {
        return $this->hasMany(ProgressMeal::class, 'nutrition_meal_id');
    }
}
