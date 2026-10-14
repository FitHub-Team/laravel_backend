<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/exercises.csv');

        if (!file_exists($path)) {
            $this->command->error("Exercises file not found: {$path}");
            return;
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            $this->command->error("Unable to open exercises.csv");
            return;
        }

        // Skip CSV header
        fgetcsv($handle);

        $count = 0;

        while (($row = fgetcsv($handle)) !== false) {

            if (count($row) < 9) {
                continue;
            }

            DB::table('exercises')->updateOrInsert(
                [
                    'id' => (int) $row[0],
                ],
                [
                    'name' => trim($row[1]),
                    'description' => trim($row[2]),
                    'type' => trim($row[3]),
                    'muscle_group' => trim($row[4]),
                    'equipment' => trim($row[5]),
                    'difficulty_level' => trim($row[6]),

                    'rating' => $row[7] !== ''
                        ? (float) $row[7]
                        : null,

                    'rating_description' => $row[8] !== ''
                        ? trim($row[8])
                        : null,

                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $count++;
        }

        fclose($handle);

        $this->command->info(
            "Successfully imported {$count} exercises."
        );
    }
}