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
        Schema::create('weekly_challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان التحدي مثل: تحدي 10,000 خطوة يوميا
            $table->text('description')->nullable(); // وصف التحدي
            $table->integer('points')->default(0); // عدد النقاط مثل: 100
            $table->boolean('is_active')->default(true); // هل التحدي نشط للأسبوع الحالي
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_challenges');
    }
};