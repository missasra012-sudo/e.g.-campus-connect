<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    // 🔔 Send Notification
    public function send(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string'
        ]);

        $notification = Notification::create([
            'title' => $request->title,
            'message' => $request->message,
            'created_by' => auth()->id()
        ]);

        return response()->json([
            'message' => 'Notification sent successfully',
            'data' => $notification
        ], 201);
    }

    // 📄 Get Notifications
    public function index()
    {
        $notifications = Notification::latest()->get();

        return response()->json($notifications);
    }
}