<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mr_ranking extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'judge_id',
        'rave_wear',
        'talent',
        'prepageant',
        'national_costume',
        'dept_uniform',
        'swim_wear',
        'formal_wear',
        'qna',
        'pageant',
        'to_top_5',
        'final',
    ];
}
