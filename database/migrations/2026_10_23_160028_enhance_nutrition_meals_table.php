<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('nutrition_meals', function (Blueprint $table) {
            if (!Schema::hasColumn('nutrition_meals', 'protein')) {
                $table->unsignedInteger('protein')->nullable()->after('calories');
            }
            if (!Schema::hasColumn('nutrition_meals', 'carbs')) {
                $table->unsignedInteger('carbs')->nullable()->after('protein');
            }
            if (!Schema::hasColumn('nutrition_meals', 'fat')) {
                $table->unsignedInteger('fat')->nullable()->after('carbs');
            }
            if (!Schema::hasColumn('nutrition_meals', 'recipe_id')) {
                $table->foreignId('recipe_id')
                    ->nullable()
                    ->after('fat')
                    ->constrained('recipes')
                    ->nullOnDelete();
            }
            if (!Schema::hasColumn('nutrition_meals', 'sort_order')) {
                $table->unsignedInteger('sort_order')->default(0)->after('time_to_eat');
            }
        });
    }


    public function down(): void
    {
        Schema::table('nutrition_meals', function (Blueprint $table) {
            if (Schema::hasColumn('nutrition_meals', 'recipe_id')) {
                $table->dropConstrainedForeignId('recipe_id');
            }

            $columns = ['protein', 'carbs', 'fat', 'sort_order'];

            foreach ($columns as $column) {
                if (Schema::hasColumn('nutrition_meals', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
