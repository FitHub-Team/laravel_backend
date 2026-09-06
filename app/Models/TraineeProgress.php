<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TraineeProgress extends Model
{
    use HasFactory;
    protected $table = 'trainee_progresses';

    protected $fillable = [
        'trainee_id',
        'coach_id',
        'weight',
        'notes',
        'progress_photo',
        'recorded_at',
        'workouts_completed',
        'coach_notes',
        'height'
    ];

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    public function exercises(): HasMany
    {
        return $this->hasMany(ProgressExercise::class);
    }
}
