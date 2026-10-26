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
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->string('health_status')->nullable();
            $table->text('chronic_diseases')->nullable();
            $table->text('previous_injuries')->nullable();
            $table->text('current_medications')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'health_status',
                'chronic_diseases',
                'previous_injuries',
                'current_medications',
            ]);
        });
    }
};
