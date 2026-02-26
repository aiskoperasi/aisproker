<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::withCount(['users', 'workPrograms'])->get();
        return view('units.index', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:KBM,Supporting',
            'description' => 'nullable|string',
        ]);

        Unit::create($request->all());

        return redirect()->route('units.index')->with('success', 'Unit berhasil ditambahkan');
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:KBM,Supporting',
            'description' => 'nullable|string',
        ]);

        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'Unit berhasil diperbarui');
    }

    public function destroy(Unit $unit)
    {
        if ($unit->users()->count() > 0) {
            return redirect()->back()->with('error', 'Unit tidak dapat dihapus karena masih memiliki user');
        }
        
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit berhasil dihapus');
    }
}
