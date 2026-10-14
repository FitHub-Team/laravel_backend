<?php

namespace App\Services\Coach;

use App\Models\Recipe;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

class RecipeService
{
    public function getRecipes(
        int $coachId,
        ?string $search = null,
        ?string $mealType = null
    ): Collection {
        $query = Recipe::query()
            ->where(function ($query) use ($coachId) {
                $query
                    ->where('coach_id', $coachId)
                    ->orWhere('is_public', true);
            });

        if ($search) {
            $query->where(function ($query) use ($search) {
                $query
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere(
                        'description',
                        'like',
                        "%{$search}%"
                    );
            });
        }

        if ($mealType) {
            $query->where('meal_type', $mealType);
        }

        return $query
            ->latest()
            ->get();
    }

    public function getRecipe(
        int $recipeId,
        int $coachId
    ): Recipe {
        return Recipe::query()
            ->where('id', $recipeId)
            ->where(function ($query) use ($coachId) {
                $query
                    ->where('coach_id', $coachId)
                    ->orWhere('is_public', true);
            })
            ->firstOrFail();
    }

    public function createRecipe(
        int $coachId,
        array $data
    ): Recipe {
        $data['coach_id'] = $coachId;

        if (
            isset($data['image']) &&
            $data['image']
        ) {
            $data['image'] = $data['image']->store(
                'recipes',
                'public'
            );
        }

        return Recipe::create($data);
    }

    public function updateRecipe(
        int $recipeId,
        int $coachId,
        array $data
    ): Recipe {
        $recipe = Recipe::query()
            ->where('id', $recipeId)
            ->where('coach_id', $coachId)
            ->firstOrFail();

        if (
            isset($data['image']) &&
            $data['image']
        ) {
            if (
                $recipe->image &&
                Storage::disk('public')->exists(
                    $recipe->image
                )
            ) {
                Storage::disk('public')->delete(
                    $recipe->image
                );
            }

            $data['image'] = $data['image']->store(
                'recipes',
                'public'
            );
        }

        $recipe->update($data);

        return $recipe->fresh();
    }

    public function deleteRecipe(
        int $recipeId,
        int $coachId
    ): void {
        $recipe = Recipe::query()
            ->where('id', $recipeId)
            ->where('coach_id', $coachId)
            ->firstOrFail();

        if (
            $recipe->image &&
            Storage::disk('public')->exists(
                $recipe->image
            )
        ) {
            Storage::disk('public')->delete(
                $recipe->image
            );
        }

        $recipe->delete();
    }
}