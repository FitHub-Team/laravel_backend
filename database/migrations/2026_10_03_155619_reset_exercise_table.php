<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->dropForeign(['exercise_id']);
        });

        Schema::dropIfExists('exercises');

        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('muscle_group')->nullable();
            $table->string('equipment')->nullable();
            $table->string('difficulty_level')->nullable();
            $table->decimal('rating', 3, 1)->default(0);
            $table->text('rating_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};