<?php

namespace App\Http\Controllers\APIs;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class studentauthcontroller extends Controller
{

    public function register(Request $request)
    {
        $data = $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required"
        ]);

        $user = User::create([
            "name" => $data['name'],
            "email" => $data['email'],
            "password" => bcrypt($data['password']),
        ]);

        $token = $user->createToken("user_token")->plainTextToken;

        $reponse = [
            "status" => 200,
            "data" => $user,
            "token" => $token,
            "message" => "Create User successfully"
        ];


        return response($reponse, 200);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // البحث عن المستخدم باستخدام البريد الإلكتروني
        $user = User::where('email', $request->email)->first();

        // التحقق من صحة البريد وكلمة المرور
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'بيانات تسجيل الدخول غير صحيحة!'], 401);
        }

        // إنشاء توكن للمستخدم
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'تم تسجيل الدخول بنجاح! 🎉',
            'token' => $token,
            'user' => $user
        ]);
    }


    public function logout (){
        Auth::user()->tokens()->delete();
        $reponse = [
            "status" => 200,

            "message" => "Logout successfully"
        ];

        return response($reponse, 200);
    }

}
