<?php

namespace App\Http\Controllers;

use App\Models\GradeLevel;
use Illuminate\Http\Request;

class GradeLevelController extends Controller
{
    public function index()
    {
        return GradeLevel::all();
    }

    public function store(Request $request)
    {
        $gradeLevel = GradeLevel::create($request->all());
        return response()->json($gradeLevel, 201);
    }

    public function show($id)
    {
        return GradeLevel::find($id);
    }

    public function update(Request $request, $id)
    {
        $gradeLevel = GradeLevel::find($id);
        $gradeLevel->update($request->all());
        return response()->json($gradeLevel);
    }

    public function destroy($id)
    {
        $gradeLevel = GradeLevel::find($id);
        $gradeLevel->delete();
        return response()->json(['message' => 'GradeLevel deleted successfully.']);
    }
}