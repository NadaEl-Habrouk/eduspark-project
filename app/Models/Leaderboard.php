<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Leaderboard extends Model
{
    protected $table = 'leaderboards';

    protected $fillable = [
        'class_code',
        'points',
        'completed_activities',
    ];
}
