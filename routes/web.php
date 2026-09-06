<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\StudentDashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
// 1. Session & Login Routes
Route::get('/', function () {
    return view('auth.login');
})->name('login');

Route::post('/session/store', [SessionController::class, 'store'])->name('session.store');
Route::post('/logout', [SessionController::class, 'destroy'])->name('logout');

Route::post('/set-locale', function (Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['ar', 'en'])) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        return response()->json(['status' => 'success', 'locale' => $locale]);
    }
    return response()->json(['status' => 'error'], 400);
});

// 2. Student Dashboard & Activities Routes
Route::middleware(['web'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // مسار عرض النشاط مع جعل المعلمات اختيارية لمنع حدوث 404
    Route::get('/activity/{category?}/{mode?}', [ActivityController::class, 'show'])->name('activity.show');
    
    // مسار جلب الأسئلة API الخاص بالطالب
    Route::get('/api/activities', [ActivityController::class, 'getActivitiesApi']); 
    
    // مسار إرسال وإلغاء/حفظ الإجابة
    Route::post('/activity/{id}/submit', [ActivityController::class, 'submitAnswer'])->name('activity.submit');
    
    Route::post('/leaderboard/update', [StudentDashboardController::class, 'updateScore'])->name('leaderboard.update');
});

// 3. Teacher Dashboard Routes
Route::middleware(['web'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/dashboard', [TeacherDashboardController::class, 'index'])->name('dashboard');
    Route::post('/evaluation/store', [TeacherDashboardController::class, 'storeEvaluation'])->name('evaluation.store');
});
// 2. Student Dashboard & Activities Routes
Route::middleware(['web'])->prefix('student')->name('student.')->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('dashboard');
    
    // مسار عرض النشاط مع جعل المعلمات اختيارية لمنع حدوث 404
    Route::get('/activity/{category?}/{mode?}', [ActivityController::class, 'show'])->name('activity.show');
    
    // مسار جلب الأسئلة API الخاص بالطالب
    Route::get('/api/activities', [ActivityController::class, 'getActivitiesApi']); 
    
    // مسار إرسال وإلغاء/حفظ الإجابة
    Route::post('/activity/{id}/submit', [ActivityController::class, 'submitAnswer'])->name('activity.submit');

    // ⭐ أضيفي هذا المسار هنا لتحديث النقاط والأنشطة
    Route::post('/leaderboard/update', [StudentDashboardController::class, 'updateScore'])->name('leaderboard.update');
});
Route::post('/set-locale', function (Request $request) {
    $locale = $request->input('locale');
    if (in_array($locale, ['ar', 'en'])) {
        Session::put('locale', $locale);
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 400);
});