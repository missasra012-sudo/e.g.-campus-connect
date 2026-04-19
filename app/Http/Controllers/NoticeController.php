<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;

class NoticeController extends Controller
{
    // 📄 Get all notices (Public)
    public function index()
    {
        return response()->json(Notice::latest()->get());
    }

    // 📄 Get single notice
    public function show($id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json([
                'message' => 'Notice not found'
            ], 404);
        }

        return response()->json($notice);
    }

    // ➕ Create notice (Protected)
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $user = auth()->user();

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
        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json([
                'message' => 'Notice not found'
            ], 404);
        }

        $user = auth()->user();

        if ($user->id !== $notice->created_by && $user->role !== 'hod') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string'
        ]);

        $notice->update($request->only(['title', 'description']));

        return response()->json([
            'message' => 'Notice updated successfully',
            'notice' => $notice
        ]);
    }

    // ❌ Delete notice
    public function destroy($id)
    {
        $notice = Notice::find($id);

        if (!$notice) {
            return response()->json([
                'message' => 'Notice not found'
            ], 404);
        }

        $user = auth()->user();

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

    // 🖼️ Upload Notice Image ✅ (FIXED POSITION)
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();

            $file->move(public_path('notice_images'), $filename);

            return response()->json([
                'message' => 'Notice image uploaded successfully',
                'image_url' => url('notice_images/'.$filename)
            ]);
        }

        return response()->json([
            'message' => 'No image uploaded'
        ], 400);
    }
}