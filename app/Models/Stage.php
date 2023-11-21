<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stage extends Model
{
    use HasFactory;

    protected $fillable = ['is_active'];

    public function categories()
    {
        return $this->hasMany(Category::class, 'category_id', 'id');
    }
}
