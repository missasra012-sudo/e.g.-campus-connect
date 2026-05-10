<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    // 📄 Get all events
    public function index()
    {
        return response()->json(Event::latest()->get());
    }

    // 📄 Get single event
    public function show($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }

        return response()->json($event);
    }

    // ➕ Create event
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_date' => 'required|date',
            'location' => 'required|string|max:255'
        ]);

        $user = auth()->user();

        // Only teacher or HOD
        if (!in_array($user->role, ['teacher', 'hod'])) {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $event = Event::create([
            'title' => $request->title,
            'description' => $request->description,
            'event_date' => $request->event_date,
            'location' => $request->location,
            'created_by' => $user->id
        ]);

        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event
        ], 201);
    }

    // ✏️ Update event
    public function update(Request $request, $id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }

        $user = auth()->user();

        // Only creator or HOD
        if ($user->id !== $event->created_by && $user->role !== 'hod') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $event->update($request->only([
            'title',
            'description',
            'event_date',
            'location'
        ]));

        return response()->json([
            'message' => 'Event updated successfully',
            'event' => $event
        ]);
    }

    // ❌ Delete event
    public function destroy($id)
    {
        $event = Event::find($id);

        if (!$event) {
            return response()->json([
                'message' => 'Event not found'
            ], 404);
        }

        $user = auth()->user();

        // Only creator or HOD
        if ($user->id !== $event->created_by && $user->role !== 'hod') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $event->delete();

        return response()->json([
            'message' => 'Event deleted successfully'
        ]);
    }

    // 📸 Upload event image
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('image')) {

            $file = $request->file('image');

            $filename = time() . '_' . uniqid() . '.' .
                $file->getClientOriginalExtension();

            $file->move(public_path('event_images'), $filename);

            return response()->json([
                'message' => 'Event image uploaded successfully',
                'image_url' => url('event_images/' . $filename)
            ]);
        }

        return response()->json([
            'message' => 'No image uploaded'
        ], 400);
    }
}