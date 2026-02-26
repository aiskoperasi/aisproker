<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Session;

class UnitScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Session::has('unit_id')) {
            $builder->where('unit_id', Session::get('unit_id'));
        } elseif (auth()->check() && auth()->user()->unit_id !== null) {
            $builder->where('unit_id', auth()->user()->unit_id);
        }
    }
}
