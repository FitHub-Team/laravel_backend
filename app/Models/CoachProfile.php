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
        'is_approved',
        'price',
        'profile_photo',
    ];

    protected $casts = [
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    

    public function skills()
    {
        return $this->belongsToMany(
            Skill::class, 
            'coach_skills',
            'coach_id',
            'skill_id'
        );
    }

    public function certifications()
    {
        return $this->hasMany(Certification::class, 'coach_id');
    }
}
