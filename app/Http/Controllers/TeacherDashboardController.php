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

        // 4. جلب كود الفصل حصرياً من مدخلات المعلم أو جلسة تسجيل المعلم (صفحة تسجيل المعلم)
        $targetClassCode = $request->input('class_code') ?? session('class_code');

        // 5. البحث عن بيانات الفصل المدخل من قِبل المعلم فقط (بدون أي افتراض للمركز الأول)
        $selectedClassData = null;
        if (!empty($targetClassCode)) {
            $selectedClassData = collect($leaderboards)->first(function ($item) use ($targetClassCode) {
                return strcasecmp($item->class_code ?? $item->code ?? '', $targetClassCode) === 0;
            });
        }

        // 6. جلب الأنشطة والحصص الخاصة بهذا الفصل فقط (ستظهر 0 إذا لم يتم إدخال الكود)
        $totalActivities = $selectedClassData ? ($selectedClassData->completed_activities ?? 0) : 0;
        
        // عدد الحصص المرتبطة بهذا الفصل (سواء من جدول التقييمات أو بناءً على وجود بيانات للفصل)
        $activeClassesCount = 0;
        if (!empty($targetClassCode)) {
            $activeClassesCount = Evaluation::where('class_code', $targetClassCode)->count();
            // إذا وُجد الفصل في جدول الصدارة ولديه أنشطة ولكن لم يُقيم بعد، نحسبه حصة واحدة افتراضياً أو نعتمد على وجوده
            if ($activeClassesCount === 0 && $selectedClassData) {
                $activeClassesCount = 1; 
            }
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

        Evaluation::create($validated);

        // حفظ كود الفصل الخاص بالمعلم في الجلسة
        session(['teacher_class_code' => $validated['class_code']]);
        session(['current_teacher_class_code' => $validated['class_code']]);

        return redirect()->route('teacher.dashboard', ['class_code' => $validated['class_code']])
                         ->with('success', 'تم حفظ التقييم وربط الفصل بنجاح.');
    }
}