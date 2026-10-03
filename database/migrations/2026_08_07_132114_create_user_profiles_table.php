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

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->date('date_of_birth')->nullable();
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            // الهدف من جدول health_goals
            $table->foreignId('goal_id')
                ->nullable()
                ->constrained('goals')
                ->nullOnDelete();

            $table->foreignId('activity_level_id')
                ->nullable()
                ->constrained('activity_levels')
                ->nullOnDelete();
            $table->text('health_condition_note')->nullable();
            $table->text('dietary_restriction_note')->nullable();
            $table->boolean('disclaimer_accepted')->default(false);

            $table->enum('training_location', [
                'home',
                'outdoor',
                'home_with_equipment',
            ])->nullable();
            $table->string('profile_photo')->nullable();

            // days available for training
            $table->json('available_days')->nullable();
            $table->timestamps();
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