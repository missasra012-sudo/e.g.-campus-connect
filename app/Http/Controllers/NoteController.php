<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Note;

class NoteController extends Controller
{
    // 📄 Get All Notes
    public function index()
    {
        return response()->json(Note::latest()->get());
    }

    // ➕ Upload Note
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'subject' => 'required|string|max:255',
            'file' => 'required|mimes:pdf,doc,docx,ppt,pptx|max:5120'
        ]);

        $user = auth()->user();

        // Only teacher or HOD
        if (!in_array($user->role, ['teacher', 'hod'])) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        if ($request->hasFile('file')) {

            $uploadedFile = $request->file('file');

            $filename = time() . '_' . uniqid() . '.' .
                $uploadedFile->getClientOriginalExtension();

            $uploadedFile->move(public_path('notes'), $filename);

            $note = Note::create([
                'title' => $request->title,
                'subject' => $request->subject,
                'file' => url('notes/' . $filename),
                'uploaded_by' => $user->id
            ]);

            return response()->json([
                'message' => 'Note uploaded successfully',
                'note' => $note
            ]);
        }

        return response()->json([
            'message' => 'File upload failed'
        ], 400);
    }

    // ❌ Delete Note
    public function destroy($id)
    {
        $note = Note::find($id);

        if (!$note) {
            return response()->json([
                'message' => 'Note not found'
            ], 404);
        }

        $user = auth()->user();

        // Only uploader or HOD
        if ($user->id !== $note->uploaded_by &&
            $user->role !== 'hod') {

            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $note->delete();

        return response()->json([
            'message' => 'Note deleted successfully'
        ]);
    }
}