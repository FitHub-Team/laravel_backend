<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            // Public exercise
            $table->foreignId('exercise_id')
                ->nullable()
                ->change();

            // Custom exercise
            $table->foreignId('custom_exercise_id')
                ->nullable()
                ->after('exercise_id')
                ->constrained('custom_exercises')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->dropForeign(['custom_exercise_id']);
            $table->dropColumn('custom_exercise_id');

            $table->foreignId('exercise_id')
                ->nullable(false)
                ->change();
        });
    }
};