<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;

class AiService
{
    public function generateNutritionPlan(array $data): array
    {
        $response = Http::timeout(1200)
            ->withHeaders(
                [
                    'x-api-key' => config('services.ai.api_key'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            )
            ->post(
                config('services.ai.base_url') . '/generate/nutrition-plan',
                $data
            );

        $response->throw();

        return $response->json();
    }
    public function generateWorkoutPlan(array $data): array
    {
        $response = Http::timeout(1200)
            ->withHeaders(
                [
                    'x-api-key' => config('services.ai.api_key'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            )
            ->post(
                config('services.ai.base_url') . '/generate/workout-plan',
                $data
            );

        $response->throw();

        return $response->json();
    }
     public function generateCalculate(array $data): array
    {
        $response = Http::timeout(1200)
            ->withHeaders(
                [
                    'x-api-key' => config('services.ai.api_key'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            )
            ->post(
                config('services.ai.base_url') . '/calculate-calories',
                $data
            );

        $response->throw();

        return $response->json();
    }
}
