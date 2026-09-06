<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentSession;

class SessionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'role' => 'required|in:student,teacher',
            'class_code' => 'required|string|max:50',
            'full_student_name' => 'required|string|max:255',
            'session_date' => 'required|date',
        ]);

        $session = StudentSession::create($validated);

        session([
            'class_code' => $session->class_code,
            'user_name' => $session->full_student_name,
            'role' => $session->role,
        ]);

        if ($session->role === 'teacher') {
            return redirect()->route('teacher.dashboard');
        }

        // تمرير البيانات للداشبورد لضمان حفظها في المتصفح
        // return redirect()->route('student.dashboard')->with([
        //     'userName' => $session->full_student_name,
        //     'classCode' => $session->class_code,
        //     'role' => $session->role
        // ]);
        return redirect()->route('student.dashboard');
    }

    public function destroy()
    {
        session()->flush();
        return redirect()->route('login');
    }
}