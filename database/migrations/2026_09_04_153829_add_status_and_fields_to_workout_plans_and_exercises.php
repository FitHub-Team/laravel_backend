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
        Schema::table('workout_plans', function (Blueprint $table) {
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
        });
        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->string('duration')->nullable();
            $table->string('equipment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workout_plans', function (Blueprint $table) {
            $table->dropColumn('status');
        });

        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->dropColumn(['duration', 'equipment']);
        });
    }
};
