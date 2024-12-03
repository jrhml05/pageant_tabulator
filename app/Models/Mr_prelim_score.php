<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mr_prelim_score extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'judge_id',
        'national_costume',
        'dept_uniform',
        'swim_wear',
        'formal_wear',
        'qna'
    ];
}
