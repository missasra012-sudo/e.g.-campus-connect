<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;

class NoticeController extends Controller
{
    // 📄 Get all notices (Public)
    public function index()
    {
        $notices = Notice::latest()->get();
        return response()->json($notices);
    }

    // 📄 Get single notice
    public function show($id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        return response()->json($notice);
    }

    // ➕ Create notice (Protected)
    public function store(Request $request)
    {
        // ✅ Check auth
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $user = auth()->user();

        // ✅ Role check
        if (!in_array($user->role, ['teacher', 'hod'])) {
            return response()->json([
                'message' => 'Unauthorized: Only teacher or HOD can create notice'
            ], 403);
        }

        $notice = Notice::create([
            'title' => $request->title,
            'description' => $request->description,
            'created_by' => $user->id
        ]);

        return response()->json([
            'message' => 'Notice created successfully',
            'notice' => $notice
        ], 201);
    }

    // ✏️ Update notice
    public function update(Request $request, $id)
    {
        // ✅ Check auth
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        $user = auth()->user();

        // ✅ Only creator or HOD
        if ($user->id !== $notice->created_by && $user->role !== 'hod') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string'
        ]);

        $notice->update($request->only('title', 'description'));

        return response()->json([
            'message' => 'Notice updated successfully',
            'notice' => $notice
        ]);
    }

    // ❌ Delete notice
    public function destroy($id)
    {
        // ✅ Check auth
        if (!auth()->check()) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json(['message' => 'Notice not found'], 404);
        }

        $user = auth()->user();

        // ✅ Only creator or HOD
        if ($user->id !== $notice->created_by && $user->role !== 'hod') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $notice->delete();

        return response()->json([
            'message' => 'Notice deleted successfully'
        ]);
    }
}