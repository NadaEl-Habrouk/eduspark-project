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
    $user = auth()->user();

    // التأكد من إنشاء السجل أو تحديثه إذا كان موجوداً
    $entry = Leaderboard::updateOrCreate(
        ['user_id' => $user->id],
        [
            'points' => \DB::raw('points + 10'),    // زيادة النقاط (عدل القيمة حسب نظامك)
            'completed' => \DB::raw('completed + 1') // زيادة عدد الأنشطة المكتملة
        ]
    );

    return response()->json([
        'success' => true,
        'points' => $entry->points,
        'completed' => $entry->completed
    ]);
}
}