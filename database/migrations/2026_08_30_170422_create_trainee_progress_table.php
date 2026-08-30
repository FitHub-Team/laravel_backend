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
            $table->text('notes')->nullable(); // ملاحظات المتدرب
            $table->string('progress_photo')->nullable(); // صورة التطور
            $table->date('recorded_at'); // تاريخ التقرير
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trainee_progresses');
    }
};