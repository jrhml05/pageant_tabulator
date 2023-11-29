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
        'prod_num',
        'sports_wear',
        'talent',
        'prepageant',
        'casual_wear',
        'formal_wear',
        'prelim',
        'final'
    ];
}
