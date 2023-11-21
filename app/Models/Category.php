<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $connection = "mysql";
    protected $fillable = [
        'category_name',
        'tags',
        'percent',
        'stage_id',
        'is_active',
    ];

    public function stage()
    {
        return $this->belongsTo(Stage::class, 'stage_id', 'id');
    }

    public function subcategorys()
    {
        return $this->hasMany(SubCategory::class, 'category_id', 'id');
    }

}
