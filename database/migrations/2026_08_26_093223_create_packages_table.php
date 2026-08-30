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
    Schema::create('packages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // معرف الكوتش
        $table->string('name'); // اسم الباقة
        $table->text('description')->nullable(); // تفاصيل الباقة
        $table->decimal('price', 8, 2); // السعر
        $table->integer('duration_in_days'); // المدة بالأيام (مثال: 30 أو 90)
        $table->boolean('is_active')->default(true); // حالة الباقة (مفعلة/معطلة)
        $table->timestamps();
    });
}
    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
