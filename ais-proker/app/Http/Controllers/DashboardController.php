<?php

namespace App\Http\Controllers;

use App\Models\WorkProgram;
use App\Models\BudgetPlan;
use App\Models\StrategicPlan;
use App\Models\QualityTarget;
use App\Models\WorkProgramUpdate;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Program Kerja Stats ──────────────────────────────────
        $programs = WorkProgram::all();
        $totalPrograms = $programs->count();
        $avgProgress = $totalPrograms ? round($programs->avg('progress')) : 0;
        $donePrograms = $programs->where('status', 'done')->count();
        $ongoingPrograms = $programs->where('status', 'on_progress')->count();
        $planningPrograms = $programs->where('status', 'planning')->count();

        // Programs needing attention (low progress, not done/cancelled)
        $atRiskPrograms = $programs
            ->whereNotIn('status', ['done', 'cancelled'])
            ->where('progress', '<', 30)
            ->take(5);

        // Top progressing programs
        $topPrograms = $programs
            ->where('status', '!=', 'cancelled')
            ->sortByDesc('progress')
            ->take(5);

        // ── Budget Stats ─────────────────────────────────────────
        $budgets = BudgetPlan::all();
        $totalBudget = $budgets->sum('amount');
        $totalRealization = $budgets->sum('realization');
        $budgetAbsorption = $totalBudget > 0 ? round(($totalRealization / $totalBudget) * 100) : 0;
        $totalWorkBudget = $programs->sum('budget');
        $totalWorkReal = $programs->sum('realization');

        // ── Strategic ─────────────────────────────────────────────
        $visi = StrategicPlan::where('type', 'visi')->first();
        $misi = StrategicPlan::where('type', 'misi')->get();
        $targets = QualityTarget::all();

        // ── Recent Activity ───────────────────────────────────────
        $recentUpdates = WorkProgramUpdate::with('workProgram')
            ->latest()
            ->take(7)
            ->get();

        // ── Status distribution for chart ─────────────────────────
        $statusChart = [
            'planning' => $planningPrograms,
            'on_progress' => $ongoingPrograms,
            'done' => $donePrograms,
            'cancelled' => $programs->where('status', 'cancelled')->count(),
        ];

        return view('dashboard', compact(
            'programs', 'totalPrograms', 'avgProgress',
            'donePrograms', 'ongoingPrograms', 'planningPrograms',
            'atRiskPrograms', 'topPrograms',
            'totalBudget', 'totalRealization', 'budgetAbsorption',
            'totalWorkBudget', 'totalWorkReal',
            'visi', 'misi', 'targets',
            'recentUpdates', 'statusChart'
        ));
    }
}
