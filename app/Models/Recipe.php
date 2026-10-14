<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recipe extends Model
{
    use HasFactory;

    protected $fillable = [
        'coach_id',
        'name',
        'description',
        'meal_type',
        'calories',
        'protein',
        'carbs',
        'fat',
        'prep_time',
        'servings',
        'ingredients',
        'instructions',
        'image',
        'is_public',
    ];

    protected $casts = [
        'ingredients' => 'array',
        'instructions' => 'array',
        'is_public' => 'boolean',
        'protein' => 'decimal:2',
        'carbs' => 'decimal:2',
        'fat' => 'decimal:2',
    ];

    protected $appends = [
        'image_url',
    ];

    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        return asset('storage/' . $this->image);
    }

    public function coach(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'coach_id'
        );
    }
}