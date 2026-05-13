<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;

class MessageController extends Controller
{
    // 📩 Send Message
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string'
        ]);

        $message = Message::create([
            'user_id' => auth()->id(),
            'message' => $request->message
        ]);

        return response()->json([
            'message' => 'Message sent successfully',
            'data' => $message
        ], 201);
    }

    // 📄 Get All Messages
    public function getMessages()
    {
        $messages = Message::latest()->get();

        return response()->json($messages);
    }
}