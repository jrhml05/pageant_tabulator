<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mr_deptuni_score extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'judge_id',
        'presentation',
        'figure',
        'beauty_poise',
        'overall_impact',
        'is_lock',

    ];
}
