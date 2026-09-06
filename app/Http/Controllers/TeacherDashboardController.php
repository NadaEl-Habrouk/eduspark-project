<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Leaderboard;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Session;

class TeacherDashboardController extends Controller
{
   public function index()
    {
        // جلب جميع الفصول وترتيبها تنازلياً حسب النقاط
        $rawLeaderboards = Leaderboard::orderBy('points', 'desc')->get();

        // حساب المراكز بحيث يكون الفصل التالي بعد المتساويين هو المركز الصحيح (1, 2, 2, 3)
        $leaderboards = [];
        $currentRank = 1;
        
        foreach ($rawLeaderboards as $index => $item) {
            // إذا لم يكن العنصر الأول، نقارن نقاطه بالعنصر السابق
            if ($index > 0) {
                $prevItem = $rawLeaderboards[$index - 1];
                // إذا كانت النقاط أقل من العنصر السابق، يصبح المركز هو ترتيب العنصر الحالي (index + 1)
                if ($item->points < $prevItem->points) {
                    $currentRank = $index + 1;
                }
                // وإذا كانت النقاط متساوية، سيحتفظ بنفس $currentRank للسابق
            }
            
            $item->calculated_rank = $currentRank;
            $leaderboards[] = $item;
        }

        return view('leaderboard.index', compact('leaderboards'));
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