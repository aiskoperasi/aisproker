<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StrategicPlan extends Model
{
    use Traits\HasSchoolYear;

    protected $fillable = ['type', 'content', 'unit_id', 'school_year_id'];
}
