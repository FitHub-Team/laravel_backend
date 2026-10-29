<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->foreignId('workout_plan_day_id')
                ->nullable()
                ->constrained('workout_plan_days')
                ->restrictOnDelete();
        });

        // ربط التمارين فقط إذا وُجد يوم مطابق واحد بالضبط
        DB::statement("
            UPDATE workout_exercises AS exercises
            SET workout_plan_day_id = days.id
            FROM (
                SELECT
                    workout_plan_id,
                    day,
                    MIN(id) AS id
                FROM workout_plan_days
                GROUP BY workout_plan_id, day
                HAVING COUNT(*) = 1
            ) AS days
            WHERE exercises.workout_plan_id = days.workout_plan_id
              AND exercises.day_of_week = days.day
              AND exercises.workout_plan_day_id IS NULL
        ");
    }

    public function down(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->dropConstrainedForeignId('workout_plan_day_id');
        });
    }
};

