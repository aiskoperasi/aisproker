<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Session;

class SchoolYearScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        if (Session::has('school_year_id')) {
            $builder->where('school_year_id', Session::get('school_year_id'));
        } else {
            // Fallback to active year if session is not set
            $activeYear = \App\Models\SchoolYear::where('is_active', true)->first();
            if ($activeYear) {
                $builder->where('school_year_id', $activeYear->id);
            }
        }
    }
}
