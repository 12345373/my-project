<?php

namespace App\Http\Controllers;

use App\Models\Friend;
use Illuminate\Http\Request;

class FriendController extends Controller
{
    public function index()
    {
        return Friend::all();
    }

    public function store(Request $request)
    {
        $friend = Friend::create($request->all());
        return response()->json($friend, 201);
    }

    public function show($id)
    {
        return Friend::find($id);
    }

    public function update(Request $request, $id)
    {
        $friend = Friend::find($id);
        $friend->update($request->all());
        return response()->json($friend);
    }

    public function destroy($id)
    {
        $friend = Friend::find($id);
        $friend->delete();
        return response()->json(['message' => 'Friend deleted successfully.']);
    }
}