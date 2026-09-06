<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $userName = session('user_name');
        $classCode = session('class_code');

        if (!$userName || !$classCode) {
            return redirect()->route('login');
        }

        $leaderboard = Leaderboard::where('class_code', $classCode)->first();

        // تمرير القيم صراحة حتى لو كانت صفراً
        $userPoints = $leaderboard ? $leaderboard->points : 0;
        $completedCount = $leaderboard ? $leaderboard->completed_activities : 0;

        return view('student.dashboard', compact('userPoints', 'completedCount'));
    }

    public function updateScore(Request $request)
    {
        $classCode = session('class_code');

        if (!$classCode) {
            return response()->json(['success' => false, 'message' => 'Class code not found'], 400);
        }

        $leaderboard = Leaderboard::firstOrCreate(
            ['class_code' => $classCode],
            [
                'points' => 0,
                'completed_activities' => 0
            ]
        );

        $leaderboard->increment('points', 10);
        $leaderboard->increment('completed_activities', 1);

        return response()->json([
            'success' => true,
            'points' => $leaderboard->points,
            'completed_activities' => $leaderboard->completed_activities
        ]);
    }
}