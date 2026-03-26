<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;

class NoticeController extends Controller
{
    public function index()
    {
        return response()->json(Notice::all());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $notice = Notice::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $notice
        ]);
    }

    public function show($id)
    {
        $notice = Notice::findOrFail($id);

        return response()->json($notice);
    }

    public function update(Request $request, $id)
    {
        $notice = Notice::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $notice->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Notice updated successfully',
            'data' => $notice
        ]);
    }

    public function destroy($id)
    {
        $notice = Notice::findOrFail($id);
        $notice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notice deleted successfully'
        ]);
    }
}