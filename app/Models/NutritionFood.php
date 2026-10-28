<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NutritionFood extends Model
{
    protected $fillable = [
        'nutrition_meal_id',
        'name',
        'calories',
        'protein',
        'carbs',
        'fat',
        'quantity',
        'unit',
        'recipe_id',
        'sort_order',
    ];

    protected $casts = [
        'calories' => 'integer',
        'protein' => 'integer',
        'carbs' => 'integer',
        'fat' => 'integer',
        'quantity' => 'float',
        'sort_order' => 'integer',
    ];

    public function meal(): BelongsTo
    {
        return $this->belongsTo(NutritionMeal::class, 'nutrition_meal_id');
    }

    public function recipe(): BelongsTo
    {
        return $this->belongsTo(Recipe::class);
    }
}
