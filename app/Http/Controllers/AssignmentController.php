<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Assignment;

class AssignmentController extends Controller
{
    // ✅ Upload Assignment
    public function uploadAssignment(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'file' => 'nullable|mimes:pdf,doc,docx|max:2048'
        ]);

        $fileName = null;

        if ($request->hasFile('file')) {

            $file = $request->file('file');

            $fileName = time() . '_' . $file->getClientOriginalName();

            $file->move(public_path('assignments'), $fileName);
        }

        $assignment = Assignment::create([
            'title' => $request->title,
            'description' => $request->description,
            'deadline' => $request->deadline,
            'file' => $fileName,
            'created_by' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Assignment uploaded successfully',
            'data' => $assignment
        ], 201);
    }

    // 📄 Get Assignments
    public function getAssignments()
    {
        return response()->json(
            Assignment::latest()->get()
        );
    }

    // 📥 Submit Assignment
    public function submitAssignment(Request $request)
    {
        $request->validate([
            'assignment_id' => 'required|exists:assignments,id',
            'file' => 'required|mimes:pdf,doc,docx|max:2048'
        ]);

        $file = $request->file('file');

        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('submissions'), $fileName);

        return response()->json([
            'message' => 'Assignment submitted successfully',
            'file' => $fileName
        ]);
    }
}