<?php

namespace App\Http\Controllers\APIs;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Controller\APIs;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        // استرداد جميع المنشورات مع بيانات المستخدم (الطالب) المرتبطة بها
        $posts = Post::with('student')->get();

        // تعديل الـ JSON Response لعرض اسم الطالب بدلاً من user_id
        $AllPosts = $posts->map(function ($post) {
            return [
                // 'id' => $post->id,
                'student_name' => $post->student->name, // اسم الطالب
                'content' => $post->content,

            ];
        });   $response =
        [
            "sattus" => 200,
            "data" => $AllPosts,
            "message" => "All posts data"

        ];
    return response($response, 200);
    }

    public function store(Request $request)
    {  $request->validate([
        'user_id' => 'required|numeric',
        'content' => 'required|string|min:6|max:300',
    ]);

    $post = new post();
    $post->user_id = $request->user_id;
    $post->content = $request->content;
    $post->save();
    $response =
        [
            "sattus" => 200,
            "data" => $post,
            "message" => "the post created successfully"
        ];
        return response($response, 200);

    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        if ($post == null) {
            $response =
                [
                    "sattus" => 404,
                    "message" => "No found posts data"
                ];
        } else {
            $response =
                [
                    "sattus" => 200,
                    "data" => $post,
                    "message" => "post data"
                ];
        }
        return response($response, 200);
    }


        public function update(Request $request, $id)
        {
            // 1. التحقق من الصحة (Validation)
            $validator = Validator::make($request->all(), [
                'content' => 'required|string|max:300', // مثال: تحقق من أن المحتوى مطلوب ولا يتجاوز 1000 حرف
                'user_id' => 'sometimes|exists:students,id', // تحقق من أن user_id موجود في جدول المستخدمين (إذا تم إرساله)
            ]);

            // إذا فشل التحقق من الصحة، أرجع رسائل الخطأ
            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422); // 422: Unprocessable Entity
            }

            // 2. البحث عن المنشور المطلوب
            $post = Post::find($id);

            // إذا لم يتم العثور على المنشور، أرجع رسالة خطأ
            if (!$post) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Post not found',
                ], 404); // 404: Not Found
            }

            // 3. تحديث المنشور بالبيانات الصحيحة
            $post->update($validator->validated());

            // 4. إرجاع استجابة JSON مع رسالة نجاح
            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully',
                'data' => $post,
            ], 200); // 200: OK
        }


    public function destroy($id)
    {
        $post = Post::find($id);
        $post->delete();
        return response()->json(['message' => 'Post deleted successfully.']);
    }}
