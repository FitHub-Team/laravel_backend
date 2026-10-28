<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('nutrition_food', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nutrition_meal_id')
                ->constrained('nutrition_meals')
                ->cascadeOnDelete();

            $table->string('name');
            $table->unsignedInteger('calories')->nullable();
            $table->unsignedInteger('protein')->nullable();
            $table->unsignedInteger('carbs')->nullable();
            $table->unsignedInteger('fat')->nullable();
            $table->decimal('quantity', 8, 2)->nullable();
            $table->string('unit')->nullable(); // قطعة، غرام، مل…
            $table->foreignId('recipe_id')
                ->nullable()
                ->constrained('recipes')
                ->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrition_food');
    }
};
