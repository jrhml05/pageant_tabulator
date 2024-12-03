<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mr_qna_score extends Model
{
    use HasFactory;

    protected $fillable = [
        'candidate_id',
        'judge_id',
        'relevance',
        'delivery',
        'content',
        'audience_impact',
        'is_lock',

    ];
}
