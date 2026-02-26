<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkProgramUpdate extends Model
{
    protected $fillable = [
        'work_program_id', 'progress_before', 'progress_after',
        'status_before', 'status_after', 'note', 'updated_by',
    ];

    public function workProgram()
    {
        return $this->belongsTo(WorkProgram::class);
    }
}
