<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrainingLocation extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];
     public function userProfiles()
    {
        return $this->hasMany(UserProfile::class);
    }
}
