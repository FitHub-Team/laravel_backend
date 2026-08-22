<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('coach_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('specialization');         // التخصص الجامعي
            $table->integer('experience_years');       // سنوات الخبرة
            $table->date('date_of_birth');             // تاريخ الميلاد
            $table->string('location');               // السكن / الإقامة
            $table->string('national_id');            // الهوية
            $table->text('bio')->nullable();           // البايو
            $table->json('certifications')->nullable(); // الشهادات
            $table->boolean('is_approved')->default(false); // موافقة الأدمن
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('coach_profiles');
    }
};