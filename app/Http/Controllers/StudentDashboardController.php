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

        // جلب البيانات من جدول الـ Leaderboard الخاص بفصل الطالب مباشرة
        $leaderboard = Leaderboard::where('class_code', $classCode)->first();

        $userPoints = $leaderboard ? (int)$leaderboard->points : 0;
        $completedCount = $leaderboard ? (int)$leaderboard->completed_activities : 0;

        // إرجاع الصفحة مع منع التخزين المؤقت (Cache) لضمان تحديث الأرقام فوراً
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

        $leaderboard = Leaderboard::firstOrCreate(
            ['class_code' => $classCode],
            [
                'points' => 0,
                'completed_activities' => 0
            ]
        );

        // زيادة النقاط والأنشطة بغض النظر عن القيمة الحالية
        $leaderboard->increment('points', 10);
        $leaderboard->increment('completed_activities', 1);

        // تحديث الكائن للحصول على القيم الجديدة بدقة
        $leaderboard->refresh();

        return response()->json([
            'success' => true,
            'points' => (int)$leaderboard->points,
            'completed_activities' => (int)$leaderboard->completed_activities
        ]);
    }
}