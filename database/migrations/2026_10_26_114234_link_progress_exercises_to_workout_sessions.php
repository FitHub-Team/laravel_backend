<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // تحقق من نفس قاعدة البيانات التي سيُنفّذ عليها التعديل
        if (DB::table('progress_exercises')->exists()) {
            throw new \RuntimeException(
                'Progress records exist. Link existing records before running this migration.'
            );
        }

        Schema::table('progress_exercises', function (Blueprint $table) {
            $table->dropUnique([
                'trainee_id',
                'workout_exercise_id',
            ]);

            $table->foreignId('workout_session_id')
                ->constrained('workout_sessions')
                ->cascadeOnDelete();

            $table->unique(
                ['workout_session_id', 'workout_exercise_id'],
                'progress_exercises_session_exercise_unique'
            );
        });
    }

    public function down(): void
    {
        // القيد القديم لا يسمح بتكرار التمرين عبر جلسات مختلفة
        $hasDuplicates = DB::table('progress_exercises')
            ->select('trainee_id', 'workout_exercise_id')
            ->groupBy('trainee_id', 'workout_exercise_id')
            ->havingRaw('COUNT(*) > 1')
            ->exists();

        if ($hasDuplicates) {
            throw new \RuntimeException(
                'Cannot restore the old unique constraint while repeated exercise records exist.'
            );
        }

        Schema::table('progress_exercises', function (Blueprint $table) {
            $table->dropUnique(
                'progress_exercises_session_exercise_unique'
            );

            $table->dropConstrainedForeignId('workout_session_id');

            $table->unique([
                'trainee_id',
                'workout_exercise_id',
            ]);
        });
    }
};