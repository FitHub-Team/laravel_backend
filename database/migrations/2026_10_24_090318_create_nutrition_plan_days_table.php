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
        Schema::create('nutrition_plan_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nutrition_plan_id')
                ->constrained('nutrition_plans')
                ->cascadeOnDelete();

            $table->enum('day', [
                'sunday',
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
            ]);

            $table->date('date')->nullable();
            $table->timestamps();

            $table->unique(['nutrition_plan_id', 'day']);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nutrition_plan_days');
    }
};
