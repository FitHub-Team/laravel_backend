<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DietaryRestriction extends Model
{
     protected $fillable = [
        'name',
        'type',
        'is_active'
     ];
      public function userProfiles()
    {
        return $this->belongsToMany(
            UserProfile::class,
            'dietary_restriction_user_profile'
        );
    }
}
