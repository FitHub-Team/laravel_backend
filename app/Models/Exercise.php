<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'muscle_group',
        'equipment',
        'difficulty_level',
        'mechanics',
        'category',
        'image',
        'force_type',
    ];

       protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];


    // العلاقة مع جدول الربط WorkoutExercise
    public function workoutExercises()
    {
        return $this->hasMany(WorkoutExercise::class);
    }
      /**
     * فلترة حسب مجموعة العضلات
     * Exercise::byMuscleGroup('chest')->get();
     */
    public function scopeByMuscleGroup($query, string $group)
    {
        return $query->where('muscle_group', $group);
    }
    //فلترة حسب مستوى الصعوبة
     public function scopeByDifficulty($query, string $level)
    {
        return $query->where('difficulty_level', $level);
    }
       /**
     * بحث بالاسم (بحث تقريبي)
     * Exercise::search('bench')->get();
     */
    public function scopeSearch($query, string $keyword)
    {
        return $query->where('name', 'like', "%{$keyword}%")
                     ->orWhere('description', 'like', "%{$keyword}%");
    }
      /**
     * اسم مستوى الصعوبة بالعربي
     * $exercise->difficulty_label
     */
    public function getDifficultyLabelAttribute(): string
    {
        return match ($this->difficulty_level) {
            'beginner'     => 'مبتدئ',
            'intermediate' => 'متوسط',
            'advanced'     => 'متقدم',
            'expert'       => 'خبير',
            default        => 'غير محدد',
        };
    }
}