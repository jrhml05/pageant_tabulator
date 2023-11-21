<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubScore extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $fillable = [
        'score_id',
        'barangay_id',
        'candidate_id',
        'category_id',
        'stage_id',
        'judge_id',
        'mastery_and_execution',
        'originality',
        'audience_impact',
    ];

    public function score()
    {
        return $this->belongsTo(Score::class, 'score_id', 'id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'sub_category_id', 'id');
    }
}
