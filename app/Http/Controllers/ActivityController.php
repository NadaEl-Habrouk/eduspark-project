<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function show(Request $request, $category = null, $mode = null)
    {
        $category = $category ?? $request->query('category');
        $mode = $mode ?? $request->query('mode', 'solo');
        $dbMode = ($mode === 'solo') ? 'individual' : $mode;
        
        $activities = Activity::where('category', $category)
                            ->where('mode', $dbMode)
                            ->get();

        if ($activities->isEmpty()) {
            $activities = Activity::where('category', $category)->get();
        }

        return view('student.activity', compact('activities', 'category', 'mode'));
    }

    // الدالة المفقودة التي تحتاجه صفحة الـ JavaScript لجلب الأسئلة
    public function getActivitiesApi(Request $request)
    {
        $category = $request->query('category');
        
        $query = Activity::query();
        if ($category) {
            $query->where('category', $category);
        }
        
        $activities = $query->get();

        return response()->json($activities);
    }

    public function submitAnswer(Request $request, $id)
    {
        $activity = Activity::findOrFail($id);
        $isCorrect = (int) $request->input('selected_option') === (int) $activity->correct_option;

        return response()->json([
            'success' => true,
            'correct' => $isCorrect,
            'points' => $isCorrect ? $activity->points : 0
        ]);
    }
}