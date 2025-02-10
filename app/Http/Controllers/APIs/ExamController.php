<?php
namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        return Exam::all();
    }

    public function store(Request $request)
    {
        $exam = Exam::create($request->all());
        return response()->json($exam, 201);
    }

    public function show($id)
    {
        return Exam::find($id);
    }

    public function update(Request $request, $id)
    {
        $exam = Exam::find($id);
        $exam->update($request->all());
        return response()->json($exam);
    }

    public function destroy($id)
    {
        $exam = Exam::find($id);
        $exam->delete();
        return response()->json(['message' => 'Exam deleted successfully.']);
    }
}