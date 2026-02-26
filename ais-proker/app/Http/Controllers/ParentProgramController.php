<?php

namespace App\Http\Controllers;

use App\Models\ParentProgram;
use App\Models\Unit;
use App\Models\SchoolYear;
use Illuminate\Http\Request;

class ParentProgramController extends Controller
{
    public function index()
    {
        $unitId = session('unit_id');
        $schoolYearId = session('school_year_id');

        $parentPrograms = ParentProgram::where('school_year_id', $schoolYearId)
            ->when($unitId, function ($query) use ($unitId) {
                return $query->where('unit_id', $unitId);
            })
            ->withCount('workPrograms')
            ->orderBy('order')
            ->get();

        return view('parent-programs.index', compact('parentPrograms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['unit_id'] = session('unit_id') ?? auth()->user()->unit_id;
        $data['school_year_id'] = session('school_year_id');

        ParentProgram::create($data);

        return redirect()->route('parent-programs.index')->with('success', 'Program Induk berhasil ditambahkan.');
    }

    public function update(Request $request, ParentProgram $parentProgram)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        if (!$parentProgram->unit_id) $data['unit_id'] = session('unit_id') ?? auth()->user()->unit_id;
        if (!$parentProgram->school_year_id) $data['school_year_id'] = session('school_year_id');

        $parentProgram->update($data);

        return redirect()->route('parent-programs.index')->with('success', 'Program Induk berhasil diperbarui.');
    }

    public function destroy(ParentProgram $parentProgram)
    {
        $parentProgram->delete();
        return redirect()->route('parent-programs.index')->with('success', 'Program Induk berhasil dihapus.');
    }
}
