<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        // Onboarding Fields
        'gender',
        'age',
        'height',
        'weight',
        'health_goal',
        'medical_conditions',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
