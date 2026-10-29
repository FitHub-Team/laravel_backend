<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ExerciseArabicSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/exercises.csv');

        if (!is_file($path)) {
            throw new RuntimeException(
                "Exercises file not found: {$path}"
            );
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException('Unable to open exercises.csv');
        }

        try {
            $headers = fgetcsv($handle, 0, ',', '"', '');

            if ($headers === false) {
                throw new RuntimeException('Exercises CSV is empty.');
            }

            // إزالة BOM إن وجد، وتنظيف أسماء الأعمدة.
            $headers[0] = preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                $headers[0]
            );

            $headers = array_map(
                fn($header) => trim((string) $header),
                $headers
            );

            $fieldMap = [
                'Title' => 'name',
                'name_ar' => 'name_ar',
                'Desc' => 'description',
                'description_ar' => 'description_ar',
                'Type' => 'type',
                'type_ar' => 'type_ar',
                'BodyPart' => 'muscle_group',
                'muscle_group_ar' => 'muscle_group_ar',
                'Equipment' => 'equipment',
                'equipment_ar' => 'equipment_ar',
                'Level' => 'difficulty_level',
                'difficulty_level_ar' => 'difficulty_level_ar',
                'Rating' => 'rating',
                'RatingDesc' => 'rating_description',
            ];

            $requiredHeaders = array_merge(
                ['source_row'],
                array_keys($fieldMap)
            );

            $missingHeaders = array_diff(
                $requiredHeaders,
                $headers
            );

            if ($missingHeaders !== []) {
                throw new RuntimeException(
                    'Missing CSV columns: '
                        . implode(', ', $missingHeaders)
                );
            }

            $count = DB::transaction(function () use (
                $handle,
                $headers,
                $fieldMap
            ) {
                $count = 0;
                $recordNumber = 1;

                while (
                    ($row = fgetcsv($handle, 0, ',', '"', '')) !== false
                ) {
                    $recordNumber++;

                    // تجاهل السطر الفارغ.
                    if ($row === [null]) {
                        continue;
                    }

                    if (count($row) !== count($headers)) {
                        throw new RuntimeException(
                            "Invalid column count at record {$recordNumber}."
                        );
                    }

                    $record = array_combine($headers, $row);

                    $id = filter_var(
                        trim((string) $record['source_row']),
                        FILTER_VALIDATE_INT,
                        ['options' => ['min_range' => 0]]
                    );

                    if ($id === false) {
                        throw new RuntimeException(
                            "Invalid source_row at record {$recordNumber}."
                        );
                    }

                    if ($id === false) {
                        throw new RuntimeException(
                            "Invalid source_row at record {$recordNumber}."
                        );
                    }

                    $values = [];

                    foreach ($fieldMap as $csvField => $dbField) {
                        $value = trim((string) $record[$csvField]);

                        $values[$dbField] = $value === ''
                            ? null
                            : $value;
                    }

                    if ($values['name'] === null) {
                        throw new RuntimeException(
                            "Missing Title at record {$recordNumber}."
                        );
                    }

                    if ($values['rating'] !== null) {
                        if (!is_numeric($values['rating'])) {
                            throw new RuntimeException(
                                "Invalid Rating at record {$recordNumber}."
                            );
                        }

                        $values['rating'] = (float) $values['rating'];
                    }

                    $existing = DB::table('exercises')
                        ->where('id', $id)
                        ->exists();

                    $values['updated_at'] = now();

                    if ($existing) {
                        DB::table('exercises')
                            ->where('id', $id)
                            ->update($values);
                    } else {
                        DB::table('exercises')->insert(
                            array_merge($values, [
                                'id' => $id,
                                'created_at' => now(),
                            ])
                        );
                    }

                    $count++;
                }

                return $count;
            });
        } finally {
            fclose($handle);
        }

        $this->command?->info(
            "Successfully imported {$count} exercises."
        );
    }
}
