<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachReview extends Model
{
    use HasFactory;

    
    protected $table = 'coach_reviews';

    protected $fillable = [
        'coach_id',
        'trainee_id',
        'rating',
    ];
    
  


    // علاقة التقييم بالكوتش
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }

    // علاقة التقييم بالمتدرب
    public function trainee()
    {
        return $this->belongsTo(User::class, 'trainee_id');
    }
}