<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Notice;
use App\Models\Feedback;
use App\Models\Image;

class DashboardController extends Controller
{
    // 📊 Dashboard Counts
    public function stats()
    {
        return response()->json([

            'total_students' => User::where('role', 'student')->count(),

            'total_notices' => Notice::count(),

            'total_feedbacks' => Feedback::count(),

            'total_images' => Image::count(),

        ]);
    }
}