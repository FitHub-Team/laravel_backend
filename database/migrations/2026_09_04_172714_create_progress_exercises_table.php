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

            $table->foreignId('trainee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('workout_exercise_id')
                ->constrained('workout_exercises')
                ->onDelete('cascade');

            $table->boolean('is_completed')->default(false);

            $table->text('notes')->nullable();
            $table->integer('sets')->default(1);           // الجولات
            $table->integer('repetitions')->nullable();    // التكرار
            $table->decimal('weight', 8, 2)->nullable();   // الوزن (كيلو)
            $table->integer('duration')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->unique(['trainee_id', 'workout_exercise_id']);
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
