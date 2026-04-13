<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // ✅ REGISTER
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:student,teacher,hod',
            'enrollment_no' => 'required_if:role,student|unique:users,enrollment_no'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->role === 'student' ? 'pending' : 'approved',
            'enrollment_no' => $request->role === 'student'
                ? $request->enrollment_no
                : null
        ]);

        return response()->json([
            'message' => 'User registered successfully',
            'user' => $user
        ], 201);
    }

    // ✅ LOGIN
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        // 🔥 approval check
        if ($user->role === 'student' && $user->status !== 'approved') {
            return response()->json([
                'message' => 'Your account is pending approval by HOD'
            ], 403);
        }

        Auth::login($user);

        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user
        ], 200);
    }

    // ✅ GET ALL PENDING STUDENTS
    public function pendingStudents()
    {
        $students = User::where('role', 'student')
            ->where('status', 'pending')
            ->get();

        return response()->json($students);
    }

    // ✅ APPROVE STUDENT
    public function approveStudent($id)
    {
        $student = User::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->status = 'approved';
        $student->save();

        return response()->json([
            'message' => 'Student approved successfully'
        ]);
    }

    // ❌ REJECT STUDENT
    public function rejectStudent($id)
    {
        $student = User::find($id);

        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }

        $student->status = 'rejected';
        $student->save();

        return response()->json([
            'message' => 'Student rejected successfully'
        ]);
    }

    // ✅ PROFILE
    public function profile()
    {
        return response()->json([
            'user' => auth()->user()
        ]);
    }
}