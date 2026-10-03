<?php

namespace Database\Seeders;

use App\Models\Goal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Goal::create([
            'title' => 'خسارة الدهون',
            'code' => 'lose_fat',
            'is_system' => true,
        ]);

        Goal::create([
            'title' => 'بناء العضلات',
            'code' => 'build_muscle',
            'is_system' => true,
        ]);

        Goal::create([
            'title' => 'الحفاظ على الوزن',
            'code' => 'maintain',
            'is_system' => true,
        ]);
    }
}
