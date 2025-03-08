<?php

namespace App\Http\Controllers\APIs;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Comment::with('student')->get();
        return response()->json([
            "status" => 200,
            "data" => $comments,
            "message" => "All comments data"
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $comment = Comment::create([
            'post_id' => $request->post_id,
            'content' => $request->content,
            'user_id' => Auth::id(), // يأخذ id المستخدم المصادق عليه
        ]);

        return response()->json([
            "status" => 200,
            "data" => $comment,
            "message" => "Comment created successfully"
        ], 201);
    }

    public function show($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(["status" => 404, "message" => "Comment not found"], 404);
        }
        return response()->json($comment, 200);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(["status" => 404, "message" => "Comment not found"], 404);
        }

        if ($comment->user_id !== Auth::id()) {
            return response()->json(["status" => 403, "message" => "Unauthorized"], 403);
        }

        $comment->update($request->only('content'));
        return response()->json($comment, 200);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return response()->json(["status" => 404, "message" => "Comment not found"], 404);
        }

        if ($comment->user_id !== Auth::id()) {
            return response()->json(["status" => 403, "message" => "Unauthorized"], 403);
        }

        $comment->delete();
        return response()->json([
            "status" => 200,
            "message" => "Comment deleted successfully"
        ], 200);
    }
}
