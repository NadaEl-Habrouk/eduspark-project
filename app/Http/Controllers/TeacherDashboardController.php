<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Session;

class TeacherDashboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. جلب اسم المعلم المسجل
        $teacherName = session('user_name') ?? session('full_student_name', 'Nada Saad');

        // 2. جلب جميع الفصول وترتيبها تنازلياً حسب النقاط للـ Leaderboard
        $rawLeaderboards = Leaderboard::orderBy('points', 'desc')->get();

        // 3. حساب المراكز بطريقة الـ Dense Ranking الصحيحة (1, 2, 2, 3)
        $leaderboards = [];
        $currentRank = 0;
        $lastPoints = null;
        
        foreach ($rawLeaderboards as $item) {
            if ($lastPoints === null || $item->points < $lastPoints) {
                $currentRank++;
                $lastPoints = $item->points;
            }
            
            $item->calculated_rank = $currentRank;
            $leaderboards[] = $item;
        }

        // 4. جلب كود الفصل حصرياً من مدخلات المعلم أو جلسة تسجيل المعلم
        $targetClassCode = $request->input('class_code') ?? session('class_code');

        // 5. البحث عن بيانات الفصل المدخل من قِبل المعلم فقط
        $selectedClassData = null;
        if (!empty($targetClassCode)) {
            $selectedClassData = collect($leaderboards)->first(function ($item) use ($targetClassCode) {
                return strcasecmp($item->class_code ?? $item->code ?? '', $targetClassCode) === 0;
            });
        }

        // 6. جلب الأنشطة والحصص الخاصة بهذا الفصل فقط
        $totalActivities = $selectedClassData ? ($selectedClassData->completed_activities ?? 0) : 0;
        
        $activeClassesCount = 0;
        if (!empty($targetClassCode)) {
            $activeClassesCount = Evaluation::where('class_code', $targetClassCode)->count();
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

        // تحديد اللغة الحالية للصفحة
        $locale = Session::get('locale', 'ar');

        // تجهيز رسالة النجاح باللغتين
        $successMessage = ($locale === 'en') 
            ? 'Evaluation saved and class linked successfully.' 
            : 'تم حفظ التقييم وربط الفصل بنجاح.';

        return redirect()->route('teacher.dashboard', ['class_code' => $validated['class_code']])
                     ->with('success', $successMessage);
    }
}