<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function uploadProfile(Request $request)
    {
        $request->validate([
            'profile_image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profile_images', 'public');

            // save in DB
            $user->profile_image = $path;
            $user->save();

            return response()->json([
                'message' => 'Profile image uploaded successfully',
                'image' => $path
            ]);
        }

        return response()->json([
            'message' => 'Image not uploaded'
        ], 400);
    }
}