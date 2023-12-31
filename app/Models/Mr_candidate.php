<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mr_candidate extends Model
{
    use HasFactory;

    public function prepageant_score(): HasMany
    {
        return $this->hasMany(Mr_prepageant_score::class, 'candidate_id', 'id');
    }

    public function talent_score(): HasMany
    {
        return $this->hasMany(Mr_talent_score::class, 'candidate_id', 'id');
    }

    public function prod_num_score(): HasMany
    {
        return $this->hasMany(Mr_prodnum_score::class, 'candidate_id', 'id');
    }

    public function sports_wear_score(): HasMany
    {
        return $this->hasMany(Mr_sportswear_score::class, 'candidate_id', 'id');
    }

    public function prelim_score(): HasMany
    {
        return $this->hasMany(Mr_prelim_score::class, 'candidate_id', 'id');
    }

    public function casual_wear_score(): HasMany
    {
        return $this->hasMany(Mr_casualwear_score::class, 'candidate_id', 'id');
    }

    public function formal_wear_score(): HasMany
    {
        return $this->hasMany(Mr_formalwear_score::class, 'candidate_id', 'id');
    }

    public function final_score(): HasMany
    {
        return $this->hasMany(Mr_final_score::class, 'candidate_id', 'id');
    }
}
