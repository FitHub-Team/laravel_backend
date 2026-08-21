<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('gender', ['male', 'female'])->nullable();
          
            $table->date('date_of_birth')->nullable();
          //  $table->unsignedInteger('age')->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            $table->enum('health_goal', [
                'weight_loss',        // خسارة وزن
                'muscle_building',    // زيادة الكتلة العضلية
                'maintain_weight',    // الحفاظ على الوزن
                'improve_endurance'   // تحسين التحمل
            ])->nullable();
            $table->text('medical_conditions')->nullable();
            $table->timestamps();
            $table->string('profile_photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_profiles');
    }
};
