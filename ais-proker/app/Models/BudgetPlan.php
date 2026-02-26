<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetPlan extends Model
{
    use Traits\HasSchoolYear;
    protected $fillable = ['code', 'description', 'amount', 'realization', 'notes', 'unit_id', 'school_year_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    protected $casts = [
        'amount' => 'float',
        'realization' => 'float',
    ];
}
