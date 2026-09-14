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
        Schema::create('progress_meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('nutrition_meal_id ')
                ->constrained('nutrition_meals')
                ->onDelete('cascade');
            $table->boolean('is_consumed')->default(false);
            $table->string('meal_image')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('consumed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_meals');
    }
};
