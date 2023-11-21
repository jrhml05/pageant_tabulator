<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrelimScore extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $connection = "mysql";

    protected $fillable = [
        'barangay_id',
        'candidate_id',
        'category_id',
        'stage_id',
        'judge_id',
        'swimsuit',
        'beauty',
        'poise',
        'evening_gown',
    ];
}
