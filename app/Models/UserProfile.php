<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProfile extends Model
{
    protected $fillable = [
        'user_id',
        // Onboarding Fields
        
        'gender',
        'age',
        'height',
        'weight',
        'health_goal',
    'disclaimer_accepted',
        'medical_conditions',
        'allergies',
        'dietary_preference',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
    'allergies' => 'array',
    'disclaimer_accepted' => 'boolean',
];

}
