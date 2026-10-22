<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use RuntimeException;
use Throwable;

class ExerciseArabicSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('data/SuperFit_exercises_ar_review.csv');

        if (!is_file($path)) {
            throw new RuntimeException("CSV not found: {$path}");
        }

        $handle = fopen($path, 'r');

        if ($handle === false) {
            throw new RuntimeException('Cannot open CSV file.');
        }

        try {
            $headers = fgetcsv($handle);

            if (!$headers) {
                throw new RuntimeException('Empty CSV file.');
            }

            $headers[0] = preg_replace(
                '/^\xEF\xBB\xBF/',
                '',
                $headers[0]
            );

            $map = [
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
                'RatingDesc' => 'rating_description',
            ];

            $required = [
                'source_row',
                'Rating',
                ...array_keys($map),
            ];

            if (array_diff($required, $headers)) {
                throw new RuntimeException(
                    'Missing CSV columns: ' .
                    implode(', ', array_diff($required, $headers))
                );
            }

            // Verify database columns before writing
            $dbColumns = Schema::getColumnListing('exercises');

            $needed = [
                'id',
                'rating',
                ...array_values($map),
            ];

            if (array_diff($needed, $dbColumns)) {
                throw new RuntimeException(
                    'Missing database columns: ' .
                    implode(', ', array_diff($needed, $dbColumns))
                );
            }

            $records = [];
            $ids = [];

            // Read and validate the entire CSV first
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) !== count($headers)) {
                    throw new RuntimeException(
                        'Invalid CSV row structure.'
                    );
                }

                $data = array_combine($headers, $row);

                $sourceId = trim($data['source_row']);

                if (!ctype_digit($sourceId)) {
                    throw new RuntimeException(
                        "Invalid source_row: {$sourceId}"
                    );
                }

                $id = (int) $sourceId;

                if (isset($ids[$id])) {
                    throw new RuntimeException(
                        "Duplicate exercise ID: {$id}"
                    );
                }

                $ids[$id] = true;

                if (trim($data['Title']) === '') {
                    throw new RuntimeException(
                        "Missing English title for ID {$id}"
                    );
                }

                $record = [
                    'id' => $id,
                ];

                foreach ($map as $csvColumn => $dbColumn) {
                    $value = trim($data[$csvColumn]);

                    $record[$dbColumn] = $value !== ''
                        ? $value
                        : null;
                }

                $rating = trim($data['Rating']);

                if ($rating !== '' && !is_numeric($rating)) {
                    throw new RuntimeException(
                        "Invalid rating for ID {$id}"
                    );
                }

                $record['rating'] = $rating !== ''
                    ? (float) $rating
                    : null;

                $records[] = $record;
            }
        } finally {
            fclose($handle);
        }

        if (empty($records)) {
            throw new RuntimeException('No exercises to import.');
        }

        // Verify zero-based, continuous AI library IDs
        sort($records);

        $sortedIds = array_keys($ids);
        sort($sortedIds, SORT_NUMERIC);

        foreach ($sortedIds as $index => $id) {
            if ($id !== $index) {
                throw new RuntimeException(
                    "AI library ID mismatch at {$index}: {$id}"
                );
            }
        }

        $this->command->info(
            'Validated ' . count($records) . ' CSV exercises.'
        );

        // MySQL normally treats explicit ID 0 as AUTO_INCREMENT.
        // Preserve source_row = 0 by enabling this SQL mode
        // temporarily for this connection.
        $connection = DB::connection();
        $isMySql = $connection->getDriverName() === 'mysql';

        $originalMode = null;

        if ($isMySql) {
            $originalMode = $connection
                ->selectOne('SELECT @@SESSION.sql_mode AS mode')
                ->mode;

            $newMode = $originalMode
                ? $originalMode . ',NO_AUTO_VALUE_ON_ZERO'
                : 'NO_AUTO_VALUE_ON_ZERO';

            $connection->statement(
                'SET SESSION sql_mode = ?',
                [$newMode]
            );
        }

        try {
            DB::transaction(function () use ($records, $ids) {

                $now = now();
                $inserted = 0;
                $updated = 0;

                foreach ($records as $record) {
                    $id = $record['id'];

                    $exists = DB::table('exercises')
                        ->where('id', $id)
                        ->exists();

                    $record['updated_at'] = $now;

                    if ($exists) {
                        // Replace all exercise fields
                        DB::table('exercises')
                            ->where('id', $id)
                            ->update($record);

                        $updated++;
                    } else {
                        $record['created_at'] = $now;

                        DB::table('exercises')
                            ->insert($record);

                        $inserted++;
                    }
                }

                // Delete exercises absent from the new dataset.
                // Foreign key constraints remain enabled.
                $deleted = DB::table('exercises')
                    ->whereNotIn('id', array_keys($ids))
                    ->delete();

                $this->command->info(
                    "Inserted: {$inserted}"
                );

                $this->command->info(
                    "Updated: {$updated}"
                );

                $this->command->info(
                    "Deleted obsolete exercises: {$deleted}"
                );
            });
        } finally {
            if ($isMySql) {
                $connection->statement(
                    'SET SESSION sql_mode = ?',
                    [$originalMode]
                );
            }
        }

        $this->command->info(
            'Exercise library import completed successfully.'
        );
    }
}
