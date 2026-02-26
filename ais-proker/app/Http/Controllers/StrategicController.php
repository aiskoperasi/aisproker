<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StrategicController extends Controller
{
    public function index()
    {
        $visi = \App\Models\StrategicPlan::where('type', 'visi')->first();
        $misi = \App\Models\StrategicPlan::where('type', 'misi')->get();
        $targets = \App\Models\QualityTarget::all();

        return view('strategic.index', compact('visi', 'misi', 'targets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:visi,misi',
            'content' => 'required|string',
        ]);

        \App\Models\StrategicPlan::create([
            'type' => $request->type,
            'content' => $request->content,
            'unit_id' => session('unit_id') ?? auth()->user()->unit_id,
            'school_year_id' => session('school_year_id') ?? \App\Models\SchoolYear::where('is_active', true)->first()->id,
        ]);

        return redirect()->back()->with('success', 'Data Visi/Misi berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $plan = \App\Models\StrategicPlan::findOrFail($id);
        $plan->update(['content' => $request->content]);

        return redirect()->back()->with('success', 'Data Visi/Misi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $plan = \App\Models\StrategicPlan::findOrFail($id);
        $plan->delete();

        return redirect()->back()->with('success', 'Data Visi/Misi berhasil dihapus.');
    }
}
