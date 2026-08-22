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
        'experience_years',
        'date_of_birth',
        'location',
        'national_id',
        'bio',
        'certifications',
        'is_approved',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'certifications' => 'array',
        'is_approved' => 'boolean',
    ];

    public function user()
    {
        return $table->belongsTo(User::class);
    }
}