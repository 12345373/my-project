<?php

namespace App\Http\Controllers\APIs;

use App\Models\User;
use Illuminate\Http\Request;
use App\Events\UserOnlineEvent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|confirmed"
        ]);

        $user = User::create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => bcrypt($data['password']),
        ]);

        $token = $user->createToken("user_token")->plainTextToken;

        return response()->json([
            "status" => 200,
            "data" => $user,
            "token" => $token,
            "message" => "User registered successfully"
        ], 200);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        $credentials = ["email" => $data['email'], "password" => $data['password']];

        if (!Auth::attempt($credentials)) {
            return response()->json([
                "status" => 400,
                "message" => "Wrong email or password"
            ], 400);
        }

        $user = auth()->user();
        $user->update(['is_online' => true]);

        broadcast(new UserOnlineEvent($user)); // بث الحدث لكل المشتركين

        $token = $user->createToken("auth_token")->plainTextToken;

        return response()->json([
            "status" => 200,
            "data" => $user,
            "token" => $token,
            "message" => "User login successfully"
        ], 200);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        if ($user) {
            $user->update(['is_online' => false]);

            broadcast(new UserOnlineEvent($user)); // تحديث الحالة للجميع

            $user->tokens()->delete(); // حذف كل التوكنز الخاصة بالمستخدم

            return response()->json(['message' => 'Logged out successfully']);
        }

        return response()->json(['message' => 'User not authenticated'], 401);
    }
}
