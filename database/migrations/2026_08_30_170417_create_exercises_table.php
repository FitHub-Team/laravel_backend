<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * id
     */
    public function up(): void
    {
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('muscle_group');
            $table->string('equipment');
            
            $table->enum('difficulty_level', [
                'beginner',
                'intermediate',
                'advanced',
                'expert'
            ]);
            $table->enum('mechanics', [
                'isolation',
                'compound',
            ]);
            $table->string('category');
            $table->string('image')->nullable();
            $table->string('force_type')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
