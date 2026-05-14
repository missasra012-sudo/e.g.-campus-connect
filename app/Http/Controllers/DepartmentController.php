<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    // ➕ Add Department
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $department = Department::create([
            'name' => $request->name
        ]);

        return response()->json([
            'message' => 'Department added successfully',
            'data' => $department
        ], 201);
    }

    // 📄 Get Departments
    public function index()
    {
        return response()->json(
            Department::latest()->get()
        );
    }
}