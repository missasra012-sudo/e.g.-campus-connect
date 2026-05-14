<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;

class CourseController extends Controller
{
    // ➕ Add Course
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,id'
        ]);

        $course = Course::create([
            'name' => $request->name,
            'department_id' => $request->department_id
        ]);

        return response()->json([
            'message' => 'Course added successfully',
            'data' => $course
        ], 201);
    }

    // 📄 Get Courses
    public function index()
    {
        return response()->json(
            Course::latest()->get()
        );
    }
}