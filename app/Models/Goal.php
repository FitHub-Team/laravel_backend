<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
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
