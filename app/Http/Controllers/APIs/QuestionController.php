<?php
namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return Question::all();
    }

    public function store(Request $request)
    {
        $question = Question::create($request->all());
        return response()->json($question, 201);
    }

    public function show($id)
    {
        return Question::find($id);
    }

    public function update(Request $request, $id)
    {
        $question = Question::find($id);
        $question->update($request->all());
        return response()->json($question);
    }

    public function destroy($id)
    {
        $question = Question::find($id);
        $question->delete();
        return response()->json(['message' => 'Question deleted successfully.']);
    }
}