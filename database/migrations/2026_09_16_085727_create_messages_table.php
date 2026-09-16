<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
         
            $table->id();

            //  المُرسِل (سواء كان كوتش أو متدرب) ومربوط بجدول المستخدمين users
            // onDelete('cascade')تعني أنه لو تم حذف المستخدم يتم حذف رسائله تلقائيا
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');

            // مُعرف المُستقبِل (الطرف الآخر في المحادثة) ومربوط أيضاً بجدول users
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');

            // نص الرسالة المُرسلة
            $table->text('message');

            // أعمدة تاريخ الإنشاء والتحديث تلقائياً (created_at, updated_at)
            $table->timestamps();
        });
    }

    public function down(): void
    {
       
        Schema::dropIfExists('messages');
    }
};