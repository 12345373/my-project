<?php

namespace App\Http\Controllers\APIs;

use App\Models\User;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
            'student_id'=>'unique:students,student_id'
        ]);
        $id =$user->id;

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
                "message" => "Dear student you are now registed go to login"

            ];
        return response($response, 200);
    }

    public function show($id)
    {
        // جلب الطالب مع البيانات المرتبطة
        $student = Student::with('gradeLevel')->find($id);

        // التحقق مما إذا كان الطالب موجودًا
        if (!$student) {
            $response = [
                "status" => 404, // تعديل الخطأ الإملائي
                "message" => "No student data found"
            ];
            return response()->json($response, 404);
        }

        // إذا كان الطالب موجودًا
        $response = [
            "status" => 200,
            "data" => $student,
            "message" => "Student data retrieved successfully"
        ];
        return response()->json($response, 200);
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
    public function search($id)
    {
        // البحث عن الطالب بناءً على student_id
        $student = Student::where('student_id', $id)->first();

        // التحقق مما إذا كان الطالب موجودًا
        if ($student) {
            $response =
            [
                "sattus" => 200,
                "data" => $student,
                "message" => "student data"
            ];
        }else{

        $response =
        [
            "sattus" => 404,
            "message" => "student not found"
        ];

    }
    return response($response, 200);
}
public function getallranks()
{
    // جلب جميع البيانات من الـ View
    $studentRanks = DB::table('student_rank_view')->select('student_id', 'student_name', 'total_points','rank')
    ->get();

    // إرجاع البيانات بصيغة JSON
    $response =
    [
        "status" => 200,
        "data" => $studentRanks,
        "message" => "student ranks "
    ];return response($response, 200);
}
public function profile()
{
    // جلب بيانات المستخدم الحالي
    $user = Auth::user();

    // التحقق مما إذا كان المستخدم مسجل دخول
    if (!$user) {
        return response()->json([
            "status" => 401,
            "message" => "Unauthorized - User not logged in"
        ], 401);
    }

    return response()->json([
        "status" => 200,
        "data" => $user,
        "message" => "User profile retrieved successfully"
    ], 200);
}

}

