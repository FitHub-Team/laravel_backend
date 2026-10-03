<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trainee_progresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainee_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->decimal('weight', 5, 2); // الوزن الحالي
            $table->text('trainee_notes')->nullable(); // ملاحظات المتدرب
            $table->string('progress_photo')->nullable(); // صورة التطور
            $table->date('recorded_at'); // تاريخ التقرير
            
                $table->integer('workouts_completed')->default(0);
                $table->text('coach_notes')->nullable();
                $table->decimal('height', 5, 2)->nullable();
        
            $table->foreignId('workout_plan_id')
                ->constrained('workout_plans')
                ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_progresses');
    }
};
