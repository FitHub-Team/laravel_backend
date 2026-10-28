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
<<<<<<< Updated upstream
=======
        'nutrition_plan_day_id',
>>>>>>> Stashed changes
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

<<<<<<< Updated upstream
=======
    public function day(): BelongsTo
    {
        return $this->belongsTo(NutritionPlanDay::class, 'nutrition_plan_day_id');
    }

    public function foods(): HasMany
    {
        return $this->hasMany(NutritionFood::class)->orderBy('sort_order')->orderBy('id');
    }

>>>>>>> Stashed changes
    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }

    public function progressMeals(): HasMany
    {
<<<<<<< Updated upstream
        return $this->hasMany(ProgressMeal::class, 'nutrition_meal_id');
=======
        return $this->hasMany(progress_meals::class, 'nutrition_meal_id');
>>>>>>> Stashed changes
    }
}
