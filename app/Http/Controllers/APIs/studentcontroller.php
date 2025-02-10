<?php

namespace App\Http\Controllers\APIs;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class studentcontroller extends Controller
{

    public function index()
    {
        $students = Student::with('gradeLevel')->get();
        $response =
            [
                "sattus" => 200,
                "data" => $students,
                "message" => "All students data"

            ];
        return response($response, 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required"
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->password = Hash::make($request->password);
        $user->email =  $request->email;
        $user->tybe = "student";
        $user->save();

        $request->validate([
            'gender' => 'required|in:Male,Female',
            'grade_level' => 'required|exists:grade_levels,id',
        ]);

        $student_id = rand(1000000, 9999999);

        $student = new Student();
        $student->user_id =  $user->id;
        $student->name =$user->name;
        $student->student_id = $student_id;

        $student->gender = $request->gender;
        $student->grade_level = $request->grade_level;
        $student->save();
        $token = $user->createToken("user_token")->plainTextToken;

        $response =
            [
                "sattus" => 200,
                "data" => $student,
                "token" => $token,
                "message" => "the student created successfully"

            ];
        return response($response, 200);
    }

    // عرض بيانات طالب محدد
    public function show($id)
    {
        $student = Student::with('gradeLevel')->findOrFail($id);

        if ($student == null) {
            $response =
                [
                    "sattus" => 404,
                    "message" => "No found student data"
                ];
        } else {
            $response =
                [
                    "sattus" => 200,
                    "data" => $student,
                    "message" => "student data"
                ];
        }
        return response($response, 200);
    }

    // تحديث بيانات طالب
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:students,email,' . $id,
            'gender' => 'required|in:Male,Female',
            'grade_level' => 'required|exists:grade_levels,id',
        ]);

        $student = Student::findOrFail($id);
        $student->name =  $request->name;
        $student->email =  $request->email;
        $student->gender =  $request->gender;
        $student->grade_level =  $request->grade_level;
       $student->save();
        return response()->json(['message' => 'Student updated successfully', 'data' => $student]);
    }

    // حذف طالب
    public function destroy($id)

    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json(['message' => 'Student deleted successfully']);
    }

}
