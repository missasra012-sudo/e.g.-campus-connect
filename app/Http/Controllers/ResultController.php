<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Result;

class ResultController extends Controller
{
    // 📊 Upload Result
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'subject' => 'required',
            'marks' => 'required|integer',
            'total_marks' => 'required|integer',
            'grade' => 'nullable',
            'semester' => 'nullable'
        ]);

        $result = Result::create($request->all());

        return response()->json([
            'message' => 'Result uploaded successfully',
            'result' => $result
        ]);
    }

    // 📄 Get All Results
    public function index()
    {
        return response()->json(Result::latest()->get());
    }

    // 👨‍🎓 Get Student Result
    public function studentResult($student_id)
    {
        $results = Result::where('student_id', $student_id)->get();

        return response()->json($results);
    }

    // ❌ Delete Result
    public function destroy($id)
    {
        $result = Result::find($id);

        if (!$result) {
            return response()->json([
                'message' => 'Result not found'
            ], 404);
        }

        $result->delete();

        return response()->json([
            'message' => 'Result deleted successfully'
        ]);
    }
}