<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\GradeLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

abstract class Controller
{
    // عرض جميع الطلاب
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

    // تخزين طالب جديد في قاعدة البيانات
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|email|unique:students,email',
            'password' => 'required|string|min:6',
            'gender' => 'required|in:Male,Female',
            'grade_level' => 'required|exists:grade_levels,id',
        ]);
        $user_id= rand(1000000,9999999);

        $student = new Student();
        $student->name = $request->name;
        $student->user_id = $user_id;
        $student->email = $request->email;
        $student->password = Hash::make($request->password);
        $student->gender = $request->gender;
        $student->grade_level = $request->grade_level;
        $student->save();

        // $student = Student::create([
        //     'user_id' => random_int(1000000, 9999999),
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'password' => Hash::make($request->password),
        //     'gender' => $request->gender,
        //     'grade_level' => $request->grade_level,
        // ]);
        $response =
            [
                "sattus" => 200,
                "data" => $student,
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
        $student->update([
            'name' => $request->name,
            'email' => $request->email,
            'gender' => $request->gender,
            'grade_level' => $request->grade_level,
        ]);

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