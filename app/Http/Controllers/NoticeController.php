<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notice;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    
    // 📌 Get all notices
    public function index()
    {
        return response()->json(Notice::all());
    }

    // 📌 Create notice
    public function store(Request $request)
    {
        // ✅ Get logged-in user
        $user = Auth::user();

        // ❌ अगर user login nahi hai
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // ❌ Student ko block karo
        if ($user->role === 'student') {
            return response()->json([
                'message' => 'Only teachers or HOD can add notices'
            ], 403);
        }

        // ✅ Validation
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // ✅ Create notice
        $notice = Notice::create([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'data' => $notice
        ], 201);
    }

    // 📌 Show single notice
    public function show($id)
    {
        $notice = Notice::findOrFail($id);

        return response()->json($notice);
    }

    // 📌 Update notice
    public function update(Request $request, $id)
    {
        dd($request->all());
        $user = Auth::user();

        // ❌ Login check
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // ❌ Student block
        if ($user->role === 'student') {
            return response()->json([
                'message' => 'Only teachers or HOD can update notices'
            ], 403);
        }

        $notice = Notice::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string'
        ]);

        $notice->update($request->only(['title', 'description']));

        return response()->json([
            'success' => true,
            'message' => 'Notice updated successfully',
            'data' => $notice
        ]);
    }

    // 📌 Delete notice
    public function destroy($id)
    {
        $user = Auth::user();

        // ❌ Login check
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        // ❌ Student block
        if ($user->role === 'student') {
            return response()->json([
                'message' => 'Only teachers or HOD can delete notices'
            ], 403);
        }

        $notice = Notice::findOrFail($id);
        $notice->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notice deleted successfully'
        ]);
    }
}