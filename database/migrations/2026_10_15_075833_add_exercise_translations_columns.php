
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->string('name_ar')->nullable();
            $table->text('description_ar')->nullable();
            $table->string('type_ar')->nullable();
            $table->string('muscle_group_ar')->nullable();
            $table->string('equipment_ar')->nullable();
            $table->string('difficulty_level_ar')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('exercises', function (Blueprint $table) {
            $table->dropColumn([
                'name_ar',
                'description_ar',
                'type_ar',
                'muscle_group_ar',
                'equipment_ar',
                'difficulty_level_ar',
            ]);
        });
    }
};
