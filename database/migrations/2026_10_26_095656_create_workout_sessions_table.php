<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('trainee_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('workout_plan_day_id')
                ->constrained('workout_plan_days')
                ->restrictOnDelete();

            // تاريخ الجلسة المجدول
            $table->date('scheduled_date');

            // pending / in_progress / completed / skipped
            $table->enum('status',['pending','inProgress','completed','skipped'])->default('pending');

            // توقيت التنفيذ الفعلي
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            // جلسة واحدة لنفس يوم الخطة والمتدرب والتاريخ
            $table->unique(
                ['trainee_id', 'workout_plan_day_id', 'scheduled_date'],
                'workout_sessions_trainee_day_date_unique'
            );

            $table->index(['trainee_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_sessions');
    }
};