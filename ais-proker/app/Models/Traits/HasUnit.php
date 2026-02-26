<?php

namespace App\Models\Traits;

use App\Models\Scopes\UnitScope;

trait HasUnit
{
    /**
     * The "booted" method of the model.
     */
    protected static function bootedHasUnit()
    {
        static::addGlobalScope(new UnitScope);
    }
}
