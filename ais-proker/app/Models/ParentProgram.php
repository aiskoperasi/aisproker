<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ParentProgram extends Model
{
    protected $fillable = ['name', 'description', 'order', 'unit_id', 'school_year_id'];

    public function workPrograms()
    {
        return $this->hasMany(WorkProgram::class, 'parent_program_id');
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }
}
