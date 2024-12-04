<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ms_candidate extends Model
{
    use HasFactory;

    public function prepageant_score(): HasMany
    {
        return $this->hasMany(Ms_prepageant_score::class, 'candidate_id', 'id');
    }

    public function talent_score(): HasMany
    {
        return $this->hasMany(Ms_talent_score::class, 'candidate_id', 'id');
    }

    public function rave_wear_score(): HasMany
    {
        return $this->hasMany(Ms_ravewear_score::class, 'candidate_id', 'id');
    }

    public function prelim_score(): HasMany
    {
        return $this->hasMany(Ms_prelim_score::class, 'candidate_id', 'id');
    }


}
