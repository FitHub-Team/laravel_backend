<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class progress_meals extends Model
{
    use HasFactory;

    protected $table = 'progress_meals';

    protected $fillable = [
        'trainee_id',
        'nutrition_meal_id',
        'is_consumed',
        'meal_image',
        'notes',
        'consumed_at',
    ];

    protected $casts = [
        'is_consumed' => 'boolean',
        'consumed_at' => 'datetime',
    ];

    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function nutritionMeal(): BelongsTo
    {
        return $this->belongsTo(NutritionMeal::class, 'nutrition_meal_id');
    }
}
