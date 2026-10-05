<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->foreignId('exercise_id')
                ->nullable()
                ->change();

            $table->string('exercise_name')->nullable();
            $table->string('target_muscle')->nullable();
            $table->string('equipment')->nullable();
            $table->string('reps')
                ->nullable()
                ->change();
        });
    }

    public function down(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->string('exercise_name')->nullable(false);
            $table->dropColumn([
                'exercise_name',
                'target_muscle',
                'equipment',
            ]);
            // رجوع reps إلى integer
            $table->integer('reps')
                ->nullable()
                ->change();
        });
    }
};
