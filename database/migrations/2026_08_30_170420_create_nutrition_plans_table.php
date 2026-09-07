<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nutrition_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('trainee_id')->constrained('users')->onDelete('cascade');
            $table->string('title'); 
            $table->integer('daily_calories')->nullable(); 
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nutrition_plans');
    }
};