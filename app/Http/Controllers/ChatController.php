<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Chat\ChatService;

class ChatController extends Controller
{
    protected ChatService $chatService;

    public function chatService(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }

    // جلب الرسائل مع مستخدم معين
    public function index(Request $request, $userId)
    {
        $messages = (new ChatService())->getConversation($userId);
        
        return response()->json([
            'status' => 'success',
            'data'   => $messages
        ]);
    }

    // إرسال رسالة جديدة
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message'     => 'required|string',
        ]);

        $message = (new ChatService())->sendMessage($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Message sent successfully',
            'data'    => $message
        ], 201);
    }
}
