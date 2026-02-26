<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityTarget extends Model
{
    use Traits\HasSchoolYear;
    protected $fillable = ['sasaran', 'target', 'achievement', 'periode', 'metode', 'unit_id', 'school_year_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
