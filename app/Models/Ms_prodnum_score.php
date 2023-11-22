<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_prodnum_score extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'judge_id',
        'mastery',
        'poise',
        'stage_presence',
    ];
}
