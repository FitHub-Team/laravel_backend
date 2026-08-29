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
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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
    public function coach()
    {
        return $this->belongsTo(User::class, 'coach_id');
    }
    public function clients()
    {
        return $this->hasMany(User::class, 'coach_id');
    }

    public function availabilities()
{
    return $this->hasMany(CoachAvailability::class, 'coach_id');
}
}
