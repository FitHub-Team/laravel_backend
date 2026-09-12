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
       Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->json('muscles')->nullable(); //بتنحفظ قائمة العضلات ك ليست 
            $table->string('goal')->nullable();     // Strength, Cardio, Mobility...
            $table->string('type')->nullable();     // Weighted, Duration, BodyWeight...
            $table->text('notes')->nullable();
            $table->text('instruction')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
