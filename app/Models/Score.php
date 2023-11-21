<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $connection = "mysql";

    protected $fillable = [
        'barangay_id',
        'candidate_id',
        'category_id',
        'stage_id',
        'judge_id',
        'talent',
        'beauty',
        'poise',
        'intelligence',
    ];

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id', 'id');
    }

    public function judge()
    {
        return $this->belongsTo(User::class, 'judge_id', 'id');
    }

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id', 'id');
    }

    public function subscore()
    {
        return $this->hasOne(SubScore::class, 'score_id', 'id');
    }


}
