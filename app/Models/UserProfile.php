<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        // 'training_location_id',
        'profile_photo',
        'available_days',
        'trainer_type',
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
            dietary_restrictions::class,
            'dietary_restriction_user_profile',
            'user_profile_id',       // المفتاح الخاص ببروفايل المستخدم
            'dietary_restriction_id' // المفتاح الخاص بالقيود الغذائية (بصيغة المفرد)
        );
    }

    public function healthConditions()
    {
        return $this->belongsToMany(
            health_conditions::class,
            'health_condition_user_profile',
            'user_profile_id',      // المفتاح الخاص ببروفايل المستخدم
            'health_condition_id'   // المفتاح الخاص بالحالات الصحية (بصيغة المفرد)
        );
    }
}
