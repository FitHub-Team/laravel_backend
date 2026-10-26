<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'chronic_diseases',
            'previous_injuries',
            'current_medications',
        ] as $column) {
            DB::statement("
                ALTER TABLE user_profiles
                ALTER COLUMN {$column} TYPE json
                USING CASE
                    WHEN {$column} IS NULL THEN NULL
                    ELSE json_build_array({$column})
                END
            ");
        }
    }

    public function down(): void
    {
        foreach ([
            'chronic_diseases',
            'previous_injuries',
            'current_medications',
        ] as $column) {
            DB::statement("
                ALTER TABLE user_profiles
                ALTER COLUMN {$column} TYPE text
                USING {$column}::text
            ");
        }
    }
};