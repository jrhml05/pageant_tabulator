<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mr_talent_score extends Model
{
    use HasFactory;
    protected $fillable = [
        'candidate_id',
        'judge_id',
        'execution',
        'originality',
        'stage_presence',
    ];
}
