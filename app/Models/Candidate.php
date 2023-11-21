<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'age',
        'barangay_id',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }
}
