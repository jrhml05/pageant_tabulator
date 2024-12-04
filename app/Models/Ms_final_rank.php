<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ms_final_rank extends Model
{
    use HasFactory;

    public function candidate()
    {
        return $this->belongsTo(Ms_candidate::class, 'candidate_id');
    }
}
