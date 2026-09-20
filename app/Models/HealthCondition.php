<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HealthCondition extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active'
    ];
     public function userProfiles()
    {
        return $this->belongsToMany(
            UserProfile::class,
            'health_condition_user_profile'
        );
    }
}
