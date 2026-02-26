<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends Model
{
    protected $fillable = [
        'name',
        'type',
        'description',
    ];

    /**
     * Get users belonging to this unit
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get work programs for this unit
     */
    public function workPrograms(): HasMany
    {
        return $this->hasMany(WorkProgram::class);
    }
}
