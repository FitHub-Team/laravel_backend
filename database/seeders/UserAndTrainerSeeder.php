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
            ['email' => 'nesma.test@gmail.com'],
            [
                'full_name' => 'Test User',
                'password' => Hash::make('Test@12345'),
                'role' => 'user',
                'email_verified_at' => now(),
            ]
        );

        UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'gender' => 'female',
                'height' => 165,
                'weight' => 60,
                'date_of_birth' => '2003-08-25',
                'activity_level_id' => 1,
                'goal_id' => 1,
                'training_location' => 'home_with_equipment',
                'disclaimer_accepted' => true,
            ]
        );

        // --- Static Coach 1 ---
        $coach1 = User::updateOrCreate(
            ['email' => 'nesmaalsousy@gmail.com'],
            [
                'full_name' => 'Test Coach',
                'password' => Hash::make('String@2026'),
                'role' => 'coach',
                'email_verified_at' => now(),
            ]
        );

        CoachProfile::updateOrCreate(
            ['user_id' => $coach1->id],
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

        // --- Static Coach 2 ---
        $coach2 = User::updateOrCreate(
            ['email' => 'sarah.coach@gmail.com'],
            [
                'full_name' => 'Sarah Coach',
                'password' => Hash::make('String@2026'),
                'role' => 'coach',
                'email_verified_at' => now(),
            ]
        );

        CoachProfile::updateOrCreate(
            ['user_id' => $coach2->id],
            [
                'specialization' => 'Yoga & Pilates',
                'experience' => 4,
                'location' => 'Ramallah',
                'birth_year' => 1998,
                'price' => 40,
                'status' => 'active',
                'is_approved' => true,
            ]
        );

        // --- Static Coach 3 ---
        $coach3 = User::updateOrCreate(
            ['email' => 'mohammad.coach@gmail.com'],
            [
                'full_name' => 'Mohammad Coach',
                'password' => Hash::make('String@2026'),
                'role' => 'coach',
                'email_verified_at' => now(),
            ]
        );

        CoachProfile::updateOrCreate(
            ['user_id' => $coach3->id],
            [
                'specialization' => 'Bodybuilding & Strength',
                'experience' => 7,
                'location' => 'Nablus',
                'birth_year' => 1992,
                'price' => 60,
                'status' => 'active',
                'is_approved' => true,
            ]
        );
    }
}