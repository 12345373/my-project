<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    // إرسال رسالة جديدة
    public function sendMessage(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'message' => 'required|string',
        ]);

        // إنشاء الرسالة في قاعدة البيانات
        $message = Message::create([
            'sender_id' => $user->id,
            'receiver_id' => $request->receiver_id,
            'message' => $request->message,
        ]);

        // بث الرسالة عبر Reverb
        broadcast(new MessageSent($message))->toOthers();

        return response()->json([
            'status' => 'Message sent successfully',
            'message' => $message
        ], 201);
    }

    // جلب جميع الرسائل بين المستخدم الحالي وشخص آخر
    public function fetchMessages(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $request->validate([
            'other_user_id' => 'required|exists:users,id',
        ]);

        $messages = Message::where(function ($query) use ($user, $request) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', $request->other_user_id);
        })->orWhere(function ($query) use ($user, $request) {
            $query->where('sender_id', $request->other_user_id)
                  ->where('receiver_id', $user->id);
        })->orderBy('created_at', 'asc')->get();

        return response()->json([
            'status' => 'Messages fetched successfully',
            'messages' => $messages
        ], 200);
    }

    // جلب قائمة الأصدقاء مع حالة الأونلاين
    public function fetchFriends(Request $request)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // جلب جميع المستخدمين الذين تم إرسال أو استلام رسائل معهم
        $friends = Message::where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender', 'receiver'])
            ->get()
            ->map(function ($message) use ($user) {
                return $message->sender_id === $user->id ? $message->receiver : $message->sender;
            })
            ->unique('id')
            ->map(function ($friend) {
                return [
                    'id' => $friend->id,
                    'name' => $friend->name,
                    'email' => $friend->email,
                    'is_online' => cache()->has('user-is-online-' . $friend->id),
                ];
            });

        return response()->json([
            'status' => 'Friends fetched successfully',
            'friends' => $friends
        ], 200);
    }
}
