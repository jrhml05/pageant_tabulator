<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Candidate extends Model
{
    protected $fillable = ['division', 'number', 'school', 'is_finalist', 'announce_order'];

    protected $casts = [
        'number' => 'integer',
        'is_finalist' => 'boolean',
        'announce_order' => 'integer',
    ];

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }

    public function scopeDivision(Builder $query, string $division): Builder
    {
        return $query->where('division', $division)->orderBy('number');
    }

    public function divisionLabel(): string
    {
        return config("pageant.divisions.{$this->division}");
    }

    /** "Ms. No. 3": short enough for card headers and error messages. */
    public function shortName(): string
    {
        return ($this->division === 'mr' ? 'Mr.' : 'Ms.')." No. {$this->number}";
    }
}
