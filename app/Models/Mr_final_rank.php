<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mr_final_rank extends Model
{
    use HasFactory;

    public function candidate()
    {
        return $this->belongsTo(Mr_candidate::class, 'candidate_id');
    }
}
