<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NutritionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'trainee_id',
        'title',
        'daily_calories',
        'protein',
        'carbs',
        'fat',
        'notes',
        'start_date',
        'end_date',
        'status',
        'version',
    ];

    protected $casts = [
        'version' => 'integer',
        'daily_calories' => 'integer',
        'protein' => 'integer',
        'carbs' => 'integer',
        'fat' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
    ];

<<<<<<< Updated upstream
    public function meals(): HasMany
    {
        return $this->hasMany(NutritionMeal::class)->orderBy('sort_order')->orderBy('id');
    }

=======
    public function days(): HasMany
    {
        return $this->hasMany(NutritionPlanDay::class);
    }

    public function meals(): HasMany
    {
        return $this->hasMany(NutritionMeal::class)->orderBy('sort_order')->orderBy('id');
    }

>>>>>>> Stashed changes
    public function trainee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
}
