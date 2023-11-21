<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalRanking extends Model
{
    use HasFactory;

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }
}
