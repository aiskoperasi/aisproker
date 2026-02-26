<?php

namespace App\Models\Traits;

use App\Models\Scopes\SchoolYearScope;

trait HasSchoolYear
{
    /**
     * The "booted" method of the model.
     */
    protected static function booted()
    {
        static::addGlobalScope(new \App\Models\Scopes\SchoolYearScope);
        static::addGlobalScope(new \App\Models\Scopes\UnitScope);
    }
}
