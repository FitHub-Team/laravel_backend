<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $path = database_path('data/exercises.json');
        //  dd(file_exists($path), $path);
        $json = file_get_contents($path);
        $exercises = json_decode($json, true);
        foreach ($exercises as $exercise) {
            DB::table('exercises')->insert([
                'name' => $exercise['name'],

                'description' => implode("\n", $exercise['instructions']),

                'muscle_group' => implode(
                    ', ',
                    array_column($exercise['muscleGroups'], 'name')
                ),

                'equipment' => implode(
                    ', ',
                    array_column($exercise['equipment'], 'name')
                ),

                'difficulty_level' => $exercise['difficultyLevel'],

                'mechanics' => $exercise['mechanics'],

                'category' => $exercise['category'],

                'image' => null,

                'force_type' => $exercise['forceType'],

                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
