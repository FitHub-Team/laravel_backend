<?php

namespace Database\Seeders;

use App\Models\ActivityLevel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class activity_levels extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         ActivityLevel::create([
            'title' => 'نشاط قليل',
            'code' => 'sedentary',
            'is_system' => true,
        ]);

        ActivityLevel::create([
            'title' => 'نشاط خفيف',
            'code' => 'light',
            'is_system' => true,
        ]);

        ActivityLevel::create([
            'title' => 'نشاط متوسط',
            'code' => 'moderate',
            'is_system' => true,
        ]);

        ActivityLevel::create([
            'title' => 'نشاط عالي',
            'code' => 'high',
            'is_system' => true,
        ]);
    }
}
