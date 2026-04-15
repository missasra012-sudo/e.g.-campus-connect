<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller

   public function uploadProfile(Request $request)
{
    $request->validate([
        'profile_image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    $user = auth()->user();

    if ($request->hasFile('profile_image')) {

        $file = $request->file('profile_image');

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        $file->move(public_path('profile_images'), $filename);

        $user->profile_image = url('profile_images/' . $filename);
        $user->save();

        return response()->json([
            'message' => 'Profile image uploaded successfully',
            'image_url' => $user->profile_image,
        ]);
    }

    return response()->json([
        'message' => 'No image uploaded'
    ], 400);
}