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
        Schema::table('trainee_progresses', function (Blueprint $table) {
            $table->foreignId('workout_plan_id')
                ->constrained('workout_plans')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trainee_progresses', function (Blueprint $table) {
            $table->dropForeign(['workout_plan_id']);
            $table->dropColumn('workout_plan_id');
        });
    }z
};
