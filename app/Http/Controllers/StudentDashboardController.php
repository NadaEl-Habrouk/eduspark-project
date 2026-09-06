<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard; // استدعاء موديل الـ Leaderboard

class StudentDashboardController extends Controller
{
    public function index()
    {
        // 1. جلب اسم الطالب وكود الفصل الحالي من الـ Session
        $userName = session('user_name');
        $classCode = session('class_code');

        // إذا لم يكن هناك تسجيل دخول، حوّله لصفحة اللوجن
        if (!$userName || !$classCode) {
            return redirect()->route('login');
        }

        // 2. البحث في جدول leaderboards عن بيانات الفصل الخاص بالطالب
        $leaderboard = Leaderboard::where('class_code', $classCode)->first();

        // 3. استخراج النقاط والأنشطة المنجزة للفصل (وإن لم يوجد سجل، يتم تعيينها بـ 0)
        $userPoints = $leaderboard ? $leaderboard->points : 0;
        $completedCount = $leaderboard ? $leaderboard->completed_activities : 0;

        // 4. إرسال البيانات لصفحة الـ Blade
        return view('student.dashboard', compact('userPoints', 'completedCount'));
    }

    // ⭐ إضافة هذه الدالة لتحديث النقاط والأنشطة للفصل عند إنهاء النشاط
    public function updateScore(Request $request)
    {
        $classCode = session('class_code');

        if (!$classCode) {
            return response()->json(['success' => false, 'message' => 'Class code not found'], 400);
        }

        // جلب سجل الفصل أو إنشاؤه تلقائياً إذا لم يكن موجوداً
        $leaderboard = Leaderboard::firstOrCreate(
            ['class_code' => $classCode],
            [
                'points' => 0,
                'completed_activities' => 0
            ]
        );

        // زيادة النقاط بمقدار 10 وزيادة عدد الأنشطة المنجزة بواقع 1
        $leaderboard->increment('points', 10);
        $leaderboard->increment('completed_activities', 1);

        return response()->json([
            'success' => true,
            'points' => $leaderboard->points,
            'completed_activities' => $leaderboard->completed_activities
        ]);
    }
}