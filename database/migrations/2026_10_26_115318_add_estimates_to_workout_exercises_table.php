<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            // الوقت المتوقع بالثواني، شامل الجولات والراحة
            $table->unsignedInteger('estimated_duration_seconds')
                ->nullable();

            // السعرات التقديرية للتمرين كاملًا
            $table->decimal('estimated_calories', 8, 2)
                ->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('workout_exercises', function (Blueprint $table) {
            $table->dropColumn([
                'estimated_duration_seconds',
                'estimated_calories',
            ]);
        });
    }
};