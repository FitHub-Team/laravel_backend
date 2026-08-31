<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('workout_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_plan_id')->constrained('workout_plans')->onDelete('cascade');
            $table->string('day_of_week'); 
            $table->string('exercise_name'); 
            $table->integer('sets')->default(3); // عدد الجولات
            $table->integer('reps')->default(12); // عدد التكرارات
            $table->string('rest_time')->nullable(); // وقت الراحة بين الجولات
            $table->text('notes')->nullable(); // ملاحظات للأداء
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('workout_exercises');
    }
};
