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
        Schema::create('progress_exercises', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('trainee_progress_id')
                ->constrained('trainee_progresses')
                ->onDelete('cascade');

            $table->foreignId('workout_exercise_id')
                ->constrained('workout_exercises')
                ->onDelete('cascade');

            $table->boolean('completed')->default(false);

            $table->integer('completed_sets')->nullable();
            $table->integer('completed_reps')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->unique([
                'trainee_progress_id',
                'workout_exercise_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_exercises');
    }
};
