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
        ?string $mealType = null,
        ?string $source = 'all' // all | public | private
    ) {
        $query = Recipe::query()
            ->where(function ($q) use ($coachId, $source) {
                if ($source === 'public') {
                    $q->where('is_public', true);
                } elseif ($source === 'private') {
                    $q->where('coach_id', $coachId)
                        ->where('is_public', false);
                } else {
                    // الافتراضي: الخاصة + العامة
                    $q->where('coach_id', $coachId)
                        ->orWhere('is_public', true);
                }
            });

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($mealType) {
            $query->where('meal_type', $mealType);
        }

        return $query->latest()->get();
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
