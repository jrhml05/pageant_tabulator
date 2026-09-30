<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OpenSegment extends Model
{
    protected $primaryKey = 'segment';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = ['segment'];
}
