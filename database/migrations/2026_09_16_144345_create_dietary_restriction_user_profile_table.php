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
        Schema::create('dietary_restriction_user_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_profile_id')
                ->constrained('user_profiles')
                ->cascadeOnDelete();

            $table->foreignId('dietary_restriction_id')
                ->constrained('dietary_restrictions')
                ->cascadeOnDelete();

            $table->unique([
                'user_profile_id',
                'dietary_restriction_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dietary_restriction_user_profile');
    }
};
