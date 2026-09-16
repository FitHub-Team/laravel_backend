<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class skill extends Model
{
    protected $fillable = [
        'name',
        'is_active'
    ];
    public function coaches()
    {
        return $this->belongsToMany(
            CoachProfile::class,
            'coach_skills',
            'skill_id',
            'coach_profile_id'
        );
    }
}
