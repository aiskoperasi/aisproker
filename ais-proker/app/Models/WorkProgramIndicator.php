<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkProgramIndicator extends Model
{
    protected $fillable = [
        'work_program_id', 'name', 'weight', 'target', 'achievement', 'evidence_file'
    ];

    protected $casts = [
        'weight' => 'float',
        'achievement' => 'float',
    ];

    public function workProgram()
    {
        return $this->belongsTo(WorkProgram::class);
    }
}
