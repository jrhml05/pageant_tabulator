<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TieDecision extends Model
{
    protected $fillable = ['segment', 'division', 'rank', 'average', 'candidates', 'order'];

    protected $casts = [
        'rank' => 'integer',
        'average' => 'integer',
        'candidates' => 'array',
        'order' => 'array',
    ];
}
