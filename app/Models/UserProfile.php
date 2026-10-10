<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserProfile extends Model
{
    protected $fillable = [
        // Onboarding Fields
        'gender',
        'height',
        'weight',
        'date_of_birth',
        'goal_id',
        'activity_level_id',
        'health_condition_note',
        'dietary_restriction_note',
        'training_location',
        'profile_photo',
        'available_days',
        'disclaimer_accepted',
    ];

    protected $casts = [
        'available_days' => 'array',
        'disclaimer_accepted' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function goal()
    {
        return $this->belongsTo(Goal::class);
    }

    public function activityLevel()
    {
        return $this->belongsTo(ActivityLevel::class);
    }

    public function trainingLocation()
    {
        return $this->belongsTo(TrainingLocation::class);
    }

    public function dietaryRestrictions()
    {
        return $this->belongsToMany(
            DietaryRestriction::class,
            'dietary_restriction_user_profile',
            'user_profile_id',
            'dietary_restriction_id'
        );
    }

    public function healthConditions()
    {
        return $this->belongsToMany(
            HealthCondition::class,
            'health_condition_user_profile',
            'user_profile_id',
            'health_condition_id'
        );
    }

    function workoutPlan() {}
    protected $appends = [
        'age',
    ];

    public function getAgeAttribute(): ?int
    {
        if (!$this->date_of_birth) {
            return null;
        }

        return Carbon::parse($this->date_of_birth)->age;
    }
}
