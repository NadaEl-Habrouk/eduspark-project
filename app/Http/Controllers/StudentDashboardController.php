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

        // جلب آخر بيانات الفصل مباشرة من قاعدة البيانات
        $leaderboard = Leaderboard::where('class_code', $classCode)->first();

        $userPoints = $leaderboard ? (int)$leaderboard->points : 0;
        $completedCount = $leaderboard ? (int)$leaderboard->completed_activities : 0;

        return response()
            ->view('student.dashboard', compact('userPoints', 'completedCount'))
            ->header('Cache-Control', 'no-store, no-cache, must-validate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    public function updateScore(Request $request)
    {
        $classCode = session('class_code');

        if (!$classCode) {
            return response()->json(['success' => false, 'message' => 'Class code not found'], 400);
        }

        // إيجاد السجل أو إنشائه بـ 0 إذا لم يكن موجوداً
        $leaderboard = Leaderboard::firstOrCreate(
            ['class_code' => $classCode],
            [
                'points' => 0,
                'completed_activities' => 0
            ]
        );

        // إضافة الزيادة بغض النظر عن القيمة الحالية
        $leaderboard->increment('points', 10);
        $leaderboard->increment('completed_activities', 1);

        // جلب البيانات بعد الزيادة للتأكد من دقتها
        $leaderboard->refresh();

        return response()->json([
            'success' => true,
            'points' => (int)$leaderboard->points,
            'completed_activities' => (int)$leaderboard->completed_activities
        ]);
    }
}