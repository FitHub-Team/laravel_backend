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
    Schema::create('coach_reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('coach_id')->constrained('users')->onDelete('cascade'); // الكوتش اللي  تقيم
        $table->foreignId('trainee_id')->constrained('users')->onDelete('cascade'); // المتدرب اللي قيم
        $table->unsignedTinyInteger('rating'); // قيمة التقييم (مثلاً من 1 لـ 5)
        $table->timestamps();
        
        // عشان المتدرب ما يقدر يقيم نفس الكوتش مرتين 
        $table->unique(['coach_id', 'trainee_id']); 
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coach_reviews');
    }
};
