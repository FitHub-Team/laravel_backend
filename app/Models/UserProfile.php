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
        'allergies',
        'dietary_preference',
        'disclaimer_accepted',
        'date_of_birth',
    ];

    protected $casts = [
        'allergies' => 'array',
        'disclaimer_accepted' => 'boolean',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
