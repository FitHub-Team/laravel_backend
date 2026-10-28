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
        Schema::table('nutrition_meals', function (Blueprint $table) {
            $table->foreignId('nutrition_plan_day_id')
                ->nullable()
                ->after('nutrition_plan_id')
                ->constrained('nutrition_plan_days')
                ->cascadeOnDelete();

            // components صار اختياري لأن الأطعمة صارت منفصلة
            $table->text('components')->nullable()->change();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nutrition_meals', function (Blueprint $table) {
            $table->dropConstrainedForeignId('nutrition_plan_day_id');
        });
    }
};
