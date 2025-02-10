<?php
namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comment= Comment::with('student')->get();
        $reponse = [
            "status" => 200,
            "data" => $comment,

            "message" => " all comments data"
        ];
    }

    public function store(Request $request)
    {
        $comment = Comment::create($request->all());
        return response()->json($comment, 201);
    }

    public function show($id)
    {
        return Comment::find($id);
    }

    public function update(Request $request, $id)
    {
        $comment = Comment::find($id);
        $comment->update($request->all());
        return response()->json($comment);
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        return response()->json(['message' => 'Comment deleted successfully.']);
    }
}
