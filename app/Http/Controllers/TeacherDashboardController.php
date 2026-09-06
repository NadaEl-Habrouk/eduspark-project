<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard;
use App\Models\Evaluation;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. جلب اسم المعلم المسجل
        $teacherName = session('user_name') ?? session('full_student_name', 'Nada Saad');

        // 2. جلب جميع الفصول وترتيبها تنازلياً حسب النقاط للـ Leaderboard
        $rawLeaderboards = Leaderboard::orderBy('points', 'desc')->get();

        // 3. حساب المراكز مع دعم تساوي النقاط (Dense Ranking)
        $leaderboards = [];
        $currentRank = 1;
        $previousPoints = null;
        $loopIndex = 0;

        foreach ($rawLeaderboards as $item) {
            $loopIndex++;
            $points = $item->points ?? 0;

            if ($previousPoints !== null && $points < $previousPoints) {
                $currentRank = $loopIndex;
            }
            
            $previousPoints = $points;
            $item->calculated_rank = $currentRank;
            $leaderboards[] = $item;
        }

        // 4. جلب كود الفصل من المدخلات أو الجلسة بمختلف مفاتيحها المحتملة
        $targetClassCode = $request->input('class_code') 
            ?? session('class_code') 
            ?? session('teacher_class_code') 
            ?? session('current_teacher_class_code');

        // 5. البحث عن بيانات الفصل المدخل 
        $selectedClassData = null;
        if (!empty($targetClassCode)) {
            $selectedClassData = collect($leaderboards)->first(function ($item) use ($targetClassCode) {
                return strcasecmp($item->class_code ?? $item->code ?? '', $targetClassCode) === 0;
            });
        }

        // 6. جلب الأنشطة والحصص الخاصة بهذا الفصل بدقة
        $totalActivities = $selectedClassData ? ($selectedClassData->completed_activities ?? 0) : 0;
        
        // حساب عدد الحصص الفعلية من جدول التقييمات بشكل ديناميكي
        $activeClassesCount = 0;
        if (!empty($targetClassCode)) {
            $activeClassesCount = Evaluation::whereRaw('LOWER(class_code) = ?', [strtolower($targetClassCode)])->count();
        }

        return view('teacher.dashboard', compact(
            'leaderboards', 
            'teacherName', 
            'activeClassesCount', 
            'totalActivities',
            'targetClassCode'
        ));
    }

    public function storeEvaluation(Request $request)
    {
        $validated = $request->validate([
            'class_code' => 'required|string|max:50',
            'engagement_rating' => 'required|integer|min:1|max:5',
            'commitment_rating' => 'required|integer|min:1|max:5',
            'notes' => 'nullable|string',
        ]);

        $validated['teacher_id'] = session('user_id', 1);

        // توحيد كود الفصل ليحفظ بأحرف متناسقة لتجنب مشاكل التطابق
        $classCode = strtoupper(trim($validated['class_code']));
        $validated['class_code'] = $classCode;

        // 1. حفظ التقييم في جدول Evaluations
        Evaluation::create($validated);

        // 2. تحديث أو إنشاء سجل الفصل في جدول Leaderboard وزيادة النقاط والأنشطة تلقائياً
        $leaderboard = Leaderboard::firstOrCreate(
            ['class_code' => $classCode],
            ['points' => 0, 'completed_activities' => 0]
        );

        $leaderboard->increment('points', 10);
        $leaderboard->increment('completed_activities', 1);

        // 3. حفظ كود الفصل في الجلسة لضمان استمراريته
        session([
            'class_code' => $classCode,
            'teacher_class_code' => $classCode,
            'current_teacher_class_code' => $classCode
        ]);

        return redirect()->route('teacher.dashboard', ['class_code' => $classCode])
                 ->with('success', 'evaluation_success');
    }
}