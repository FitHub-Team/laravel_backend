<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

  
    protected $fillable = [
        'sender_id',   // معرف المستخدم المرسل
        'receiver_id', // معرف المستخدم المستقبل
        'message',     // محتوى نص الرسالة
    ];

    /**
     * علاقة كل رسالة بالمُرسِل )
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    /**
     * علاقة كل رسالة بالمُستقبِل 
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
