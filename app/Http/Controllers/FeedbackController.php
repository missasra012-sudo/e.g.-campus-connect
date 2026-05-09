<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    // 📄 Get All Feedbacks
    public function index()
    {
        return response()->json(
            Feedback::latest()->get()
        );
    }

    // ➕ Submit Feedback
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $feedback = Feedback::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message
        ]);

        return response()->json([
            'message' => 'Feedback submitted successfully',
            'feedback' => $feedback
        ], 201);
    }

    // 📄 Single Feedback
    public function show($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json([
                'message' => 'Feedback not found'
            ], 404);
        }

        return response()->json($feedback);
    }

    // ❌ Delete Feedback
    public function destroy($id)
    {
        $feedback = Feedback::find($id);

        if (!$feedback) {
            return response()->json([
                'message' => 'Feedback not found'
            ], 404);
        }

        $user = auth()->user();

        // only creator or HOD
        if ($feedback->user_id != $user->id && $user->role != 'hod') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $feedback->delete();

        return response()->json([
            'message' => 'Feedback deleted successfully'
        ]);
    }
}