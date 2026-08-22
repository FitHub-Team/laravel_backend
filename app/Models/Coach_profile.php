<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coach_profile extends Model
{
    protected $fillable = [
        'user_id',
        'specialization',
        'experience',
        'location',
        'birth_year',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
