<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    // List all images
    public function index()
    {
        $images = Image::all();
        return response()->json([
            'success' => true,
            'data' => $images
        ]);
    }

    // Upload new image
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|max:2048'
        ]);

        $path = $request->file('image')->store('images', 'public');

        $image = Image::create([
            'title' => $request->title,
            'path' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Image uploaded successfully',
            'data' => $image
        ]);
    }

    // View single image
    public function show($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $image
        ]);
    }

    // Update image info
    public function update(Request $request, $id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'image' => 'sometimes|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($image->path);
            $image->path = $request->file('image')->store('images', 'public');
        }

        if ($request->has('title')) {
            $image->title = $request->title;
        }

        $image->save();

        return response()->json([
            'success' => true,
            'message' => 'Image updated successfully',
            'data' => $image
        ]);
    }

    // Delete image
    public function destroy($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'success' => false,
                'message' => 'Image not found'
            ], 404);
        }

        Storage::disk('public')->delete($image->path);
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully'
        ]);
    }
}