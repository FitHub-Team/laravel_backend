<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OnboardingTest extends TestCase
{
    use RefreshDatabase;
    public function test_userData(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $payload = [
            'gender' => 'female',
            'age' => 22,
            'height' => 165,
            'weight' => 58,
            'health_goal' => 'muscle_building',
            'medical_conditions' => 'None',
            'allergies' => ['Peanuts', 'Lactose'],
            'dietary_preference' => 'Keto',
            'disclaimer_accepted' => true,
        ];


        // endpoint to send request
        $response = $this->postJson('/api/onboarding/complete', $payload);
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'تم حفظ بيانات الملف الشخصي بنجاح'
            ]);

        // sure that data in database
        $this->assertDatabaseHas('user_profiles', [
            'user_id' => $user->id,
            'gender' => 'female',
            'health_goal' => 'muscle_building',
            'dietary_preference' => 'Keto',
            'disclaimer_accepted' => 1,
        ]);
    }
}
