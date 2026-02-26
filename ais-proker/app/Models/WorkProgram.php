<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkProgram extends Model
{
    use Traits\HasSchoolYear;

    protected $fillable = [
        'name', 'parent_name', 'description', 'pj', 'unit', 'timeline',
        'indicators', 'budget', 'realization', 'notes',
        'progress', 'status', 'sop_file',
        'unit_id', 'school_year_id', 'parent_program_id',
    ];

    protected $casts = [
        'budget' => 'float',
        'realization' => 'float',
        'progress' => 'integer',
    ];


    public function parentProgram()
    {
        return $this->belongsTo(ParentProgram::class, 'parent_program_id');
    }

    public function updates()
    {
        return $this->hasMany(WorkProgramUpdate::class)->latest();
    }

    public function orgUnit()
    {
        return $this->belongsTo(Unit::class, 'unit_id');
    }

    public function schoolYear()
    {
        return $this->belongsTo(SchoolYear::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
                'planning' => 'Planning',
                'on_progress' => 'On Progress',
                'done' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                default => 'Planning',
            };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
                'planning' => 'amber',
                'on_progress' => 'blue',
                'done' => 'emerald',
                'cancelled' => 'red',
                default => 'amber',
            };
    }

    public function weightedIndicators()
    {
        return $this->hasMany(WorkProgramIndicator::class);
    }

    public function getProgressAttribute($value)
    {
        if ($this->weightedIndicators()->count() > 0) {
            $totalWeighted = $this->weightedIndicators->sum(function ($indicator) {
                return ($indicator->achievement * $indicator->weight) / 100;
            });
            return round($totalWeighted);
        }

        return $value;
    }

    public function getProgressColorAttribute(): string
    {
        $progress = $this->progress;
        if ($progress >= 100)
            return 'emerald';
        if ($progress >= 60)
            return 'blue';
        if ($progress >= 30)
            return 'amber';
        return 'red';
    }
}
