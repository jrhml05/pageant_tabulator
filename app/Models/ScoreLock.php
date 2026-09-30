<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScoreLock extends Model
{
    protected $fillable = ['segment', 'judge_id'];
}
