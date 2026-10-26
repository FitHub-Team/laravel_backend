<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegistrationOptionsSeeder extends Seeder
{
    public function run(): void
    {
        // Health conditions
        $healthConditions = [
            ['name' => 'لا توجد حالات صحية', 'description' => 'لا توجد حالات صحية معلنة'],
            ['name' => 'السكري', 'description' => 'حالة تتعلق بمستويات سكر الدم'],
            ['name' => 'ارتفاع ضغط الدم', 'description' => 'ارتفاع ضغط الدم'],
            ['name' => 'أمراض القلب', 'description' => 'حالات متعلقة بصحة القلب'],
            ['name' => 'مشاكل المفاصل', 'description' => 'آلام أو مشاكل في المفاصل'],
            ['name' => 'مشاكل الغدة الدرقية', 'description' => 'حالات متعلقة بالغدة الدرقية'],
            ['name' => 'الربو', 'description' => 'حالة تنفسية'],
            ['name' => 'آلام الظهر', 'description' => 'آلام في الظهر'],
        ];

        foreach ($healthConditions as $condition) {
            DB::table('health_conditions')->updateOrInsert(
                ['name' => $condition['name']],
                [
                    'description' => $condition['description'],
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        // Dietary restrictions
        $dietaryRestrictions = [
            ['name' => 'لا توجد قيود غذائية', 'type' => 'preference'],
            ['name' => 'حساسية اللاكتوز', 'type' => 'allergy'],
            ['name' => 'حساسية الغلوتين', 'type' => 'allergy'],
            ['name' => 'حساسية المكسرات', 'type' => 'allergy'],
            ['name' => 'حساسية المأكولات البحرية', 'type' => 'allergy'],
            ['name' => 'نظام غذائي نباتي', 'type' => 'preference'],
            ['name' => 'نظام غذائي خالٍ من اللحوم', 'type' => 'preference'],
            ['name' => 'تجنب الأطعمة الحارة', 'type' => 'preference'],
        ];

        foreach ($dietaryRestrictions as $restriction) {
            DB::table('dietary_restrictions')->updateOrInsert(
                ['name' => $restriction['name']],
                [
                    'type' => $restriction['type'],
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
