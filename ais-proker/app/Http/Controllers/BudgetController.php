<?php

namespace App\Http\Controllers;

use App\Models\BudgetPlan;
use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index()
    {
        $budgets = BudgetPlan::orderBy('code')->get();
        return view('budget.index', compact('budgets'));
    }

    public function edit($id)
    {
        $budget = BudgetPlan::findOrFail($id);
        return view('budget.edit', compact('budget'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'realization' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        $budget = BudgetPlan::findOrFail($id);
        $budget->update([
            'code' => $request->code,
            'description' => $request->description,
            'amount' => $request->amount,
            'realization' => $request->realization,
            'notes' => $request->notes,
        ]);

        return redirect()->route('budget.index')
            ->with('success', 'Item anggaran berhasil diperbarui!');
    }

    public function create()
    {
        return view('budget.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|min:0',
            'realization' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        BudgetPlan::create([
            'code' => $request->code,
            'description' => $request->description,
            'amount' => $request->amount,
            'realization' => $request->realization ?? 0,
            'notes' => $request->notes,
            'unit_id' => session('unit_id') ?? auth()->user()->unit_id,
            'school_year_id' => session('school_year_id'),
        ]);

        return redirect()->route('budget.index')
            ->with('success', 'Item anggaran berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        BudgetPlan::findOrFail($id)->delete();
        return redirect()->route('budget.index')
            ->with('success', 'Item anggaran berhasil dihapus.');
    }
}
