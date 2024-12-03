<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_natlcost_score extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'judge_id',
        'design',
        'stage_presence',
        'poise_bearing',
        'overall_impact',
        'is_lock',

    ];
}
