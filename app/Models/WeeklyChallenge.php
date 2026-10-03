<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyChallenge extends Model
{
    use HasFactory;

    protected $table = 'weekly_challenges';

    protected $fillable = [
        'title',
        'description',
        'points',
        'is_active',
    ];
}