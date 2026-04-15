<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class ProfileController extends Controller
{
    // 👤 Get Profile
    public function profile(Request $request)
    {
        return response()->json([
            'user' => $request->user()
        ]);
    }

    // ✏️ Update Profile
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
        ]);

        $user->update($request->only('name', 'email'));

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }

    // 🔒 Change Password
    public function changePassword(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        // ❌ wrong current password
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect'
            ], 400);
        }

        // ✅ update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password changed successfully'
        ]);
    }

     public function uploadProfile(Request $request)

    $user = $request->user();

    // validation
    $request->validate([
        'image' => 'required|image|mimes:jpg,jpeg,png|max:2048'
    ]);

    // old image delete (optional but best practice)
    if ($user->profile_image && File::exists(public_path($user->profile_image))) {
        File::delete(public_path($user->profile_image));
    }

    // image upload
    $image = $request->file('image');
    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();

    $image->move(public_path('profile_images'), $imageName);

    // save path in DB
    $user->profile_image = 'profile_images/' . $imageName;
    $user->save();

    return response()->json([
        'message' => 'Profile image uploaded successfully',
        'image_url' => url($user->profile_image)
    ]);
}