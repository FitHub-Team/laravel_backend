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
        Schema::create('health_condition_user_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_profile_id')
                ->constrained('user_profiles')
                ->cascadeOnDelete();

            $table->foreignId('health_condition_id')
                ->constrained('health_conditions')
                ->cascadeOnDelete();

            $table->unique([
                'user_profile_id',
                'health_condition_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_condition_user_profile');
    }
};
