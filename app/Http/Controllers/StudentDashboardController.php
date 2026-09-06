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


public function updateLeaderboard(Request $request)
{
    $classCode = session('class_code');

    if (!$classCode) {
        return response()->json(['success' => false, 'message' => 'Unauthorized or no class code found'], 401);
    }

    // البحث والتحديث باستخدام كود الفصل الموجود في الـ Session
    $entry = Leaderboard::updateOrCreate(
        ['class_code' => $classCode],
        [
            'points' => \DB::raw('COALESCE(points, 0) + 10'),
            'completed_activities' => \DB::raw('COALESCE(completed_activities, 0) + 1')
        ]
    );

    // إعادة تحميل السجل لجلب القيم الرقمية الحقيقية بدلاً من كائنات الـ DB raw
    $entry->refresh();

    return response()->json([
        'success' => true,
        'points' => (int) $entry->points,
        'completed' => (int) $entry->completed_activities
    ]);
}
}