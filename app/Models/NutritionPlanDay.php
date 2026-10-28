<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NutritionPlanDay extends Model
{
    protected $fillable = [
        'nutrition_plan_id',
        'day',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function nutritionPlan(): BelongsTo
    {
        return $this->belongsTo(NutritionPlan::class);
    }

    public function meals(): HasMany
    {
        return $this->hasMany(NutritionMeal::class)
            ->orderBy('sort_order')
            ->orderBy('id');
    }
}
