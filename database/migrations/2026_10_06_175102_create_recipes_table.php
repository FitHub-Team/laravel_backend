<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recipes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('coach_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->string('name');

            $table->text('description')->nullable();

            $table->enum('meal_type', [
                'breakfast',
                'lunch',
                'dinner',
                'snack',
            ]);

            $table->unsignedInteger('calories')->nullable();

            $table->decimal('protein', 8, 2)->nullable();
            $table->decimal('carbs', 8, 2)->nullable();
            $table->decimal('fat', 8, 2)->nullable();

            $table->unsignedInteger('prep_time')->nullable();
            $table->unsignedInteger('servings')->default(1);
            $table->json('ingredients');

            $table->json('instructions')->nullable();

            $table->string('image')->nullable();

            $table->boolean('is_public')->default(false);

            $table->timestamps();

            $table->index('coach_id');
            $table->index('meal_type');
            $table->index('is_public');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recipes');
    }
};