<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    protected $fillable = [
        'coach_id',
        'title',
        'issuer',
        'year',
    ];
    public function coach()
    {
        return $this->belongsTo(CoachProfile::class);
    }
}
