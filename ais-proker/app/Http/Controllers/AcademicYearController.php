<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AcademicYearController extends Controller
{
    public function setYear(Request $request)
    {
        $request->validate([
            'school_year_id' => 'required|exists:school_years,id',
        ]);

        $year = \App\Models\SchoolYear::findOrFail($request->school_year_id);
        
        $request->session()->put('school_year_id', $year->id);
        $request->session()->put('school_year_name', $year->name);

        return redirect()->back()->with('success', 'Tahun Akademik berhasil diubah ke ' . $year->name);
    }

    public function setUnit(Request $request)
    {
        $request->validate([
            'unit_id' => 'nullable|exists:units,id',
        ]);

        if ($request->unit_id) {
            $unit = \App\Models\Unit::findOrFail($request->unit_id);
            $request->session()->put('unit_id', $unit->id);
            $request->session()->put('unit_name', $unit->name);
        } else {
            $request->session()->forget(['unit_id', 'unit_name']);
        }

        return redirect()->back()->with('success', 'Filter Unit berhasil diperbarui');
    }
}
