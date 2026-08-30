<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'duration_in_days',
        'is_active',
    ];

    // علاقة الباقة بالكوتش
    public function coach()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
