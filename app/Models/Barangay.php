<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    use HasFactory;

    public function candidate()
    {
        return $this->hasOne(Candidate::class, 'barangay_id', 'id');
    }

    public function scores()
    {
        return $this->hasMany(Score::class, 'barangay_id', 'id');
    }

    public function subscores()
    {
        return $this->hasMany(SubScore::class, 'barangay_id', 'id');
    }

    public function talentscore()
    {
        // return $this->subscores->
    }

    public function prelimscores()
    {
        return $this->hasMany(PrelimScore::class, 'barangay_id', 'id');
    }

    public function semifinalscores()
    {
        return $this->hasMany(SemifinalScore::class, 'barangay_id', 'id');
    }

    public function finalscores()
    {
        return $this->hasMany(FinalScore::class, 'barangay_id', 'id');
    }

    public function finalrankings()
    {
        return $this->hasOne(FinalRanking::class, 'barangay_id', 'id');
    }
}
