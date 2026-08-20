<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationCode extends Model
{
     protected $fillable = ['user_id', 'code', 'expires_at'];

    protected $casts = [
        'expires_at' => 'datetime',
    ];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
      public function isExpired(): bool
    {
        return Carbon::now()->isAfter($this->expires_at);
    }
     public function isValid(string $code): bool
    {
        return !$this->isExpired() && $this->code === $code;
    }
}
