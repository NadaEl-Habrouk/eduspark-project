<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $table = 'activities';

    protected $fillable = [
        'category',
        'mode',
        'title',
        'description',
        'options',
        'correct_option',
        'badge', // أضفنا الحقل الناقص هنا
        'points',
    ];

    // تحويل حقول الـ JSON إلى مصفوفات PHP والعكس تلقائياً
    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'options' => 'array',
    ];
}