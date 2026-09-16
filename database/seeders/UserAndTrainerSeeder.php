<?php

namespace Database\Seeders;

use App\Models\CoachProfile;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserAndTrainerSeeder extends Seeder
{
    public function run(): void
    {
        // Static User
        $user = User::updateOrCreate(
            ['email' => 'testuser@example.com'],
            [
                'full_name' => 'Test User',
                'password' => Hash::make('12345678'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            []
        );

        // Static Coach
        $coach = User::updateOrCreate(
            ['email' => 'coach@example.com'],
            [
                'full_name' => 'Test Coach',
                'password' => Hash::make('12345678'),
                'role' => 'coach',
                'email_verified_at' => now(),
            ]
        );

        CoachProfile::updateOrCreate(
            ['user_id' => $coach->id],
            [
                'specialization' => 'Fitness',
                'experience' => 5,
                'location' => 'Gaza',
                'birth_year' => 1995,
                'price' => 50,
                'status' => 'active',
                'is_approved' => true,
            ]
        );
    }
}