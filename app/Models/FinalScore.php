<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalScore extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $connection = "mysql";

    protected $fillable = [
        'barangay_id',
        'candidate_id',
        'stage_id',
        'judge_id',
        'intelligence',
        'beauty',
        'is_lock',
    ];
}
