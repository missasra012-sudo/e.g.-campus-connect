<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    // ✅ Mark Attendance
    public function markAttendance(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent'
        ]);

        $attendance = Attendance::create([
            'student_id' => $request->student_id,
            'date' => $request->date,
            'status' => $request->status
        ]);

        return response()->json([
            'message' => 'Attendance marked successfully',
            'data' => $attendance
        ], 201);
    }

    // 📄 Get Attendance
    public function getAttendance()
    {
        $attendance = Attendance::latest()->get();

        return response()->json($attendance);
    }
}