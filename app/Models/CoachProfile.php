<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoachProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specialization',
        'experience',
        'birth_year',
        'location',
        'national_id',
        'bio',
        'certifications',
        'is_approved',
        'rejection_reason',
        'price',
        'profile_photo',
    ];

    protected $casts = [

        'certifications' => 'array',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function skills()
    {
        return $this->belongsToMany(
            Skill::class,
            'coach_skills',
            'coach_profile_id',
            'skill_id'
        );
    }
}
