<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'password',
        'role',
        'panel',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function isJudge(): bool
    {
        return $this->role === 'judge';
    }

    /**
     * Judges on a panel, in seat order: reports label them Judge 1, 2, ... by account creation.
     */
    public function scopeOnPanel(Builder $query, string $panel): Builder
    {
        return $query->where('role', 'judge')->where('panel', $panel)->orderBy('id');
    }
}
