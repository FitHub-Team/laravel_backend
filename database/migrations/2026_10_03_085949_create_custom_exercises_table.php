<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations. id

     */
    public function up(): void
    {
        Schema::create('custom_exercises', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained('users')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('muscle_group');
            $table->string('equipment')->nullable();
            $table->string('difficulty');
            $table->text('instructions');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_exercises');
    }
};
