<?php

namespace App\Http\Controllers\Api\coach\WorkoutPlan;

use App\Http\Controllers\Controller;
use App\Models\CustomExercise;
use Illuminate\Http\Request;

class CustomExerciseController extends Controller
{
    /**
     * Get all custom exercises for the authenticated coach.
     */
    public function index(Request $request)
    {
        $exercises = CustomExercise::where(
            'coach_id',
            $request->user()->id
        )
            ->when($request->search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->when($request->muscle_group, function ($query, $muscleGroup) {
                $query->where('muscle_group', $muscleGroup);
            })
            ->when($request->difficulty, function ($query, $difficulty) {
                $query->where('difficulty', $difficulty);
            })
            ->when($request->equipment, function ($query, $equipment) {
                $query->where('equipment', $equipment);
            })
            ->latest()
            ->paginate(
                min((int) $request->get('per_page', 15), 100)
            );

        return response()->json([
            'data' => $exercises->items(),
            'meta' => [
                'current_page' => $exercises->currentPage(),
                'last_page'    => $exercises->lastPage(),
                'per_page'     => $exercises->perPage(),
                'total'        => $exercises->total(),
            ],
        ]);
    }

    /**
     * Create a new custom exercise.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'muscle_group' => ['required', 'string', 'max:255'],
            'equipment' => ['nullable', 'string', 'max:255'],
            'difficulty' => ['required', 'string', 'max:255'],
            'instructions' => ['required', 'string'],
        ]);

        $validated['coach_id'] = $request->user()->id;

        $exercise = CustomExercise::create($validated);

        return response()->json([
            'message' => 'Custom exercise created successfully.',
            'data' => $exercise,
        ], 201);
    }

    /**
     * Get one custom exercise.
     */
    public function show(Request $request, int $id)
    {
        $exercise = $this->findOwnedOrFail($request, $id);

        return response()->json([
            'data' => $exercise,
        ]);
    }

    /**
     * Update a custom exercise.
     */
    public function update(Request $request, int $id)
    {
        $exercise = $this->findOwnedOrFail($request, $id);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'muscle_group' => ['sometimes', 'required', 'string', 'max:255'],
            'equipment' => ['nullable', 'string', 'max:255'],
            'difficulty' => ['sometimes', 'required', 'string', 'max:255'],
            'instructions' => ['sometimes', 'required', 'string'],
        ]);

        $exercise->update($validated);

        return response()->json([
            'message' => 'Custom exercise updated successfully.',
            'data' => $exercise->fresh(),
        ]);
    }

    /**
     * Delete a custom exercise.
     */
    public function destroy(Request $request, int $id)
    {
        $exercise = $this->findOwnedOrFail($request, $id);

        $exercise->delete();

        return response()->json([
            'message' => 'Custom exercise deleted successfully.',
        ]);
    }

    /**
     * Find a custom exercise owned by the authenticated coach.
     */
    private function findOwnedOrFail(Request $request, int $id): CustomExercise
    {
        return CustomExercise::where('id', $id)
            ->where('coach_id', $request->user()->id)
            ->firstOrFail();
    }
}
