<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard;

class StudentDashboardController extends Controller
{
    public function index()
{
    $user = auth()->user();
    $classCode = $user->class_code ?? 'GENERAL_CLASS';

    // جلب أو إنشاء سجل المتصدرين لهذا الفصل ليبدأ دائماً من 0
    $leaderboard = Leaderboard::firstOrCreate(
        ['class_code' => $classCode],
        [
            'points' => 0,
            'completed_activities' => 0
        ]
    );

    return view('student.dashboard', compact('leaderboard'));
}

   public function updateScore(Request $request)
{
    // افترض أن الطالب لديه كود فصل مخزن في جدول المستخدمين، أو حدد كود افتراضي
    $user = auth()->user();
    $classCode = $user->class_code ?? 'GENERAL_CLASS'; 

    // البحث عن سجل الفصل أو إنشائه تلقائياً بـ 0 نقاط إذا لم يكن موجوداً
    $leaderboard = Leaderboard::firstOrCreate(
        ['class_code' => $classCode],
        [
            'points' => 0,
            'completed_activities' => 0
        ]
    );

    // زيادة النقاط والأنشطة المنجزة
    $leaderboard->points += 10; // أو عدد النقاط المخصصة للسؤال
    $leaderboard->completed_activities += 1;
    $leaderboard->save();

    return response()->json([
        'success' => true,
        'points' => $leaderboard->points,
        'completed_activities' => $leaderboard->completed_activities
    ]);
}
}