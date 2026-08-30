<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraineeProgress extends Model
{
    use HasFactory;
    protected $table = 'trainee_progresses';

    protected $fillable = ['trainee_id', 'coach_id', 'weight', 'notes', 'progress_photo', 'recorded_at'];

    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }
}