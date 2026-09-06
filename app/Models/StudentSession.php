<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSession extends Model
{
    protected $table = 'student_sessions';

    protected $fillable = [
        'role',
        'class_code',
        'full_student_name',
        'session_date',
    ];
}
