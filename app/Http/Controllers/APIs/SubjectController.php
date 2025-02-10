<?php
namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return Subject::all();
    }

    public function store(Request $request)
    {
        $subject = Subject::create($request->all());
        return response()->json($subject, 201);
    }

    public function show($id)
    {
        return Subject::find($id);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);
        $subject->update($request->all());
        return response()->json($subject);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);
        $subject->delete();
        return response()->json(['message' => 'Subject deleted successfully.']);
    }
}