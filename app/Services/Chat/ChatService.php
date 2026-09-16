<?php
namespace App\Services\Chat;

use App\Models\Message;
use App\Events\MessageSent;
use Illuminate\Support\Facades\DB;

class ChatService
{
    public function sendMessage(array $data): Message
    {
        return DB::transaction(function () use ($data) {
            // 1. حفظ الرسالة في قاعدة البيانات
            $message = Message::create([
                'sender_id'   => auth()->id(), // المرسل هو المستخدم المسجل حالياً
                'receiver_id' => $data['receiver_id'],
                'message'     => $data['message'],
            ]);

         
            broadcast(new MessageSent($message))->toOthers();

            return $message;
        });
    }

    /**
     * جلب المحادثة بين المستخدم الحالي ومستخدم آخر
     */
    public function getConversation(int $userId)
    {
        $currentUserId = auth()->id();

        return Message::where(function ($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $currentUserId)
                      ->where('receiver_id', $userId);
            })
            ->orWhere(function ($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $userId)
                      ->where('receiver_id', $currentUserId);
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }
}