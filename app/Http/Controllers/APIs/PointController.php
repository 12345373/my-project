<?php
namespace App\Http\Controllers;

use App\Models\Point;
use Illuminate\Http\Request;

class PointController extends Controller
{
    public function index()
    {
        return Point::all();
    }

    public function store(Request $request)
    {
        $point = Point::create($request->all());
        return response()->json($point, 201);
    }

    public function show($id)
    {
        return Point::find($id);
    }

    public function update(Request $request, $id)
    {
        $point = Point::find($id);
        $point->update($request->all());
        return response()->json($point);
    }

    public function destroy($id)
    {
        $point = Point::find($id);
        $point->delete();
        return response()->json(['message' => 'Point deleted successfully.']);
    }
}