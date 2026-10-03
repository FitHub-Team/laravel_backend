<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLevel extends Model
{
    protected $fillable = [
        'title',
        'is_active',
        'code',
        'is_system',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function userProfiles()
    {
        return $this->hasMany(UserProfile::class);
    }
}
