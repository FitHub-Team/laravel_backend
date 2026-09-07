<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'role',
        'profile_image',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function userProfile()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function coachProfile()
    {
        return $this->hasOne(Coach_profile::class);
    }

    public function emailVerificationCodes()
    {
        return $this->hasMany(EmailVerificationCode::class);
    }

    public function getLatestVerificationCode()
    {
        return $this->emailVerificationCodes()
            ->latest()
            ->first();
    }



    public function availabilities()
    {
        return $this->hasMany(CoachAvailability::class, 'coach_id');
    }


    public function profile()
    {
        return $this->userProfile();
    }
}
