<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('nutrition_plans', function (Blueprint $table) {
            if (!Schema::hasColumn('nutrition_plans', 'start_date')) {
                $table->date('start_date')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('nutrition_plans', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            if (!Schema::hasColumn('nutrition_plans', 'status')) {
                $table->enum('status', [
                    'draft',
                    'active',
                    'completed',
                    'cancelled',
                ])->default('draft')->after('end_date');
            }
            if (!Schema::hasColumn('nutrition_plans', 'version')) {
                $table->unsignedInteger('version')->default(1)->after('status');
            }
            if (!Schema::hasColumn('nutrition_plans', 'protein')) {
                $table->unsignedInteger('protein')->nullable()->after('daily_calories');
            }
            if (!Schema::hasColumn('nutrition_plans', 'carbs')) {
                $table->unsignedInteger('carbs')->nullable()->after('protein');
            }
            if (!Schema::hasColumn('nutrition_plans', 'fat')) {
                $table->unsignedInteger('fat')->nullable()->after('carbs');
            }
        });
    }

    public function down(): void
    {
        Schema::table('nutrition_plans', function (Blueprint $table) {
            $columns = [
                'start_date',
                'end_date',
                'status',
                'version',
                'protein',
                'carbs',
                'fat',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('nutrition_plans', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
