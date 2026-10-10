<?php

namespace App\Http\Controllers\Api\coach;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecipeRequest;
use App\Http\Requests\UpdateRecipeRequest;
use App\Services\Coach\RecipeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    public function __construct(
        private readonly RecipeService $recipeService
    ) {
    }

    public function index(Request $request): JsonResponse
    {
        $recipes = $this->recipeService->getRecipes(
            $request->user()->id,
            $request->query('search'),
            $request->query('meal_type')
        );

        return response()->json([
            'status' => true,
            'data' => $recipes,
        ]);
    }

    public function store(
        StoreRecipeRequest $request
    ): JsonResponse {
        $recipe = $this->recipeService->createRecipe(
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'status' => true,
            'message' => 'Recipe created successfully.',
            'data' => $recipe,
        ], 201);
    }

    public function show(
        Request $request,
        int $id
    ): JsonResponse {
        $recipe = $this->recipeService->getRecipe(
            $id,
            $request->user()->id
        );

        return response()->json([
            'status' => true,
            'data' => $recipe,
        ]);
    }

    public function update(
        UpdateRecipeRequest $request,
        int $id
    ): JsonResponse {
        $recipe = $this->recipeService->updateRecipe(
            $id,
            $request->user()->id,
            $request->validated()
        );

        return response()->json([
            'status' => true,
            'message' => 'Recipe updated successfully.',
            'data' => $recipe,
        ]);
    }

    public function destroy(
        Request $request,
        int $id
    ): JsonResponse {
        $this->recipeService->deleteRecipe(
            $id,
            $request->user()->id
        );

        return response()->json([
            'status' => true,
            'message' => 'Recipe deleted successfully.',
        ]);
    }
}