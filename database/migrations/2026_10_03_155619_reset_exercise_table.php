<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('exercises');

        Schema::enableForeignKeyConstraints();

        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('type')->nullable();
            $table->string('muscle_group')->nullable();
            $table->string('equipment')->nullable();
            $table->string('difficulty_level')->nullable();
            $table->decimal('rating', 3, 1)->nullable();
            $table->text('rating_description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();

        Schema::dropIfExists('exercises');

        Schema::enableForeignKeyConstraints();
    }
};