<?php

namespace App\Http\Controllers;

use App\Models\WorkProgram;
use App\Models\ParentProgram;
use App\Models\Unit;
use App\Models\SchoolYear;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    public function index()
    {
        $unitId = session('unit_id');
        $schoolYearId = session('school_year_id');
        $unit = $unitId ? Unit::find($unitId) : null;
        $schoolYear = SchoolYear::find($schoolYearId);

        return view('reports.index', compact('unit', 'schoolYear'));
    }

    public function exportUnitReport(Request $request)
    {
        $data = $this->getUnitReportData($request);
        $type = $request->get('type', 'detailed');
        $view = $type === 'overview' ? 'reports.overview_report' : 'reports.unit_report';
        
        $pdf = Pdf::loadView($view, $data);
        
        $prefix = $type === 'overview' ? 'Ikhtisar_' : 'Laporan_';
        $filename = $prefix . 'Program_' . ($data['unit'] ? str_replace(' ', '_', $data['unit']->name) : 'Semua_Unit') . '_' . str_replace('/', '-', $data['schoolYear']->name) . '.pdf';
        
        return $pdf->stream($filename);
    }

    private function getUnitReportData(Request $request)
    {
        $unitId = session('unit_id');
        $schoolYearId = session('school_year_id');
        $unit = $unitId ? Unit::find($unitId) : null;
        $schoolYear = SchoolYear::find($schoolYearId);
        $type = $request->get('type', 'detailed');

        $parentPrograms = ParentProgram::where('school_year_id', $schoolYearId)
            ->when($unitId, function ($query) use ($unitId) {
                return $query->where('unit_id', $unitId);
            })
            ->when($request->get('parent_program_id'), function ($query, $pId) {
                return $query->where('id', $pId);
            })
            ->with(['workPrograms' => function ($query) use ($unitId, $schoolYearId, $request) {
                $query->withoutGlobalScopes();
                if ($unitId) $query->where('unit_id', $unitId);
                $query->where('school_year_id', $schoolYearId);
                
                if ($request->get('status')) {
                    $query->where('status', $request->get('status'));
                }
            }])
            ->orderBy('order')
            ->get();

        $totalPrograms = 0;
        $completedPrograms = 0;
        $totalBudget = 0;
        $totalRealization = 0;
        $totalProgress = 0;

        foreach ($parentPrograms as $parent) {
            $parentTotalProgress = 0;
            $parentCount = 0;
            foreach ($parent->workPrograms as $wp) {
                $totalPrograms++;
                if ($wp->status === 'done') $completedPrograms++;
                $totalBudget += $wp->budget;
                $totalRealization += $wp->realization;
                $totalProgress += $wp->progress;
                $parentTotalProgress += $wp->progress;
                $parentCount++;
            }
            $parent->avg_progress = $parentCount > 0 ? round($parentTotalProgress / $parentCount) : 0;
            $parent->wp_count = $parentCount;
        }

        $avgProgress = $totalPrograms > 0 ? round($totalProgress / $totalPrograms) : 0;
        $completionRate = $totalPrograms > 0 ? round(($completedPrograms / $totalPrograms) * 100) : 0;

        return [
            'unit' => $unit,
            'schoolYear' => $schoolYear,
            'parentPrograms' => $parentPrograms,
            'stats' => [
                'total' => $totalPrograms,
                'completed' => $completedPrograms,
                'budget' => $totalBudget,
                'realization' => $totalRealization,
                'avg_progress' => $avgProgress,
                'completion_rate' => $completionRate
            ],
            'date' => date('d F Y'),
            'type' => $type
        ];
    }

    public function exportQualityReport()
    {
        $data = $this->getQualityReportData();
        $pdf = Pdf::loadView('reports.quality_report', $data);
        $filename = 'Laporan_Sasaran_Mutu_' . ($data['unit'] ? str_replace(' ', '_', $data['unit']->name) : 'Semua_Unit') . '_' . str_replace('/', '-', $data['schoolYear']->name) . '.pdf';
        return $pdf->stream($filename);
    }

    private function getQualityReportData()
    {
        $unitId = session('unit_id');
        $schoolYearId = session('school_year_id');
        $unit = $unitId ? Unit::find($unitId) : null;
        $schoolYear = SchoolYear::find($schoolYearId);

        $orderedNames = [
            'Pengadaan Dan Perbaikan Sarana Dan Prasarana',
            'Pengadaan Barang Dan Jasa',
            'Pemeliharaan Dan Perbaikan',
            'Layanan Penggunaan Fasilitas',
            'Peminjaman Dan Pengembalian Barang',
            'Penggunaan Barang Habis Pakai',
            'Keamanan, Kenyamanan Dan Kondusifitas Lingkungan Sekolah',
            'Kebersihan Dan Keindahan Sekolah',
            'Pembinaan Anggota Security Dan Tim OB'
        ];

        $programs = \App\Models\WorkProgram::where('school_year_id', $schoolYearId)
            ->when($unitId, function($q) use ($unitId) {
                return $q->where('unit_id', $unitId);
            })->get();

        $groupedProgress = $programs->groupBy(function ($item) {
            $name = strtoupper(preg_replace('/\s+/', ' ', trim($item->parent_name)));
            $aliases = [
                'PENGADAN BARANG DAN JASA' => 'PENGADAAN BARANG DAN JASA',
                'PEMINJAMAN DAN PENGMBALIAN BARANG' => 'PEMINJAMAN DAN PENGEMBALIAN BARANG',
                'PENGGUNAAN BARANG HABI PAKAI' => 'PENGGUNAAN BARANG HABIS PAKAI',
            ];
            $normalized = $aliases[$name] ?? $name;
            return $normalized;
        })->map(function ($group) {
            return $group->avg('progress');
        });

        $targets = \App\Models\QualityTarget::all();

        foreach ($targets as $index => $target) {
            $smPosition = $index + 1;
            $newAchievement = 0;

            if ($smPosition == 1) {
                $newAchievement = $groupedProgress->get(strtoupper($orderedNames[0])) ?? 0;
            }
            elseif ($smPosition == 2) {
                $newAchievement = $groupedProgress->get(strtoupper($orderedNames[1])) ?? 0;
            }
            elseif ($smPosition == 3) {
                $newAchievement = $groupedProgress->get(strtoupper($orderedNames[2])) ?? 0;
            }
            elseif ($smPosition == 4) {
                $p4 = $groupedProgress->get(strtoupper($orderedNames[3])) ?? 0;
                $p5 = $groupedProgress->get(strtoupper($orderedNames[4])) ?? 0;
                $p6 = $groupedProgress->get(strtoupper($orderedNames[5])) ?? 0;
                $newAchievement = ($p4 + $p5 + $p6) / 3;
            }
            elseif ($smPosition == 5) {
                $newAchievement = $groupedProgress->get(strtoupper($orderedNames[6])) ?? 0;
            }
            elseif ($smPosition == 6) {
                $newAchievement = $groupedProgress->get(strtoupper($orderedNames[7])) ?? 0;
            }
            $target->achievement = round($newAchievement, 2);
        }

        $avgAchievement = $targets->count() > 0 ? $targets->avg('achievement') : 0;
        $avgTarget = $targets->count() > 0 ? $targets->avg('target') : 0;
        $overallProgress = $avgTarget > 0 ? ($avgAchievement / $avgTarget) * 100 : 0;

        return [
            'unit' => $unit,
            'schoolYear' => $schoolYear,
            'targets' => $targets,
            'stats' => [
                'total' => $targets->count(),
                'avg_achievement' => $avgAchievement,
                'effectiveness' => $overallProgress
            ],
            'date' => date('d F Y')
        ];
    }

    public function exportBudgetReport()
    {
        $data = $this->getBudgetReportData();
        $pdf = Pdf::loadView('reports.budget_report', $data);
        $filename = 'Laporan_APBS_' . ($data['unit'] ? str_replace(' ', '_', $data['unit']->name) : 'Semua_Unit') . '_' . str_replace('/', '-', $data['schoolYear']->name) . '.pdf';
        return $pdf->stream($filename);
    }

    private function getBudgetReportData()
    {
        $unitId = session('unit_id');
        $schoolYearId = session('school_year_id');
        $unit = $unitId ? Unit::find($unitId) : null;
        $schoolYear = SchoolYear::find($schoolYearId);

        $budgets = \App\Models\BudgetPlan::orderBy('code')->get();

        $totalPagu = $budgets->sum('amount');
        $totalReal = $budgets->sum('realization');
        $absorption = $totalPagu > 0 ? round(($totalReal / $totalPagu) * 100) : 0;

        return [
            'unit' => $unit,
            'schoolYear' => $schoolYear,
            'budgets' => $budgets,
            'stats' => [
                'total_pagu' => $totalPagu,
                'total_real' => $totalReal,
                'sisa' => $totalPagu - $totalReal,
                'absorption' => $absorption
            ],
            'date' => date('d F Y')
        ];
    }

    public function exportBundle(Request $request)
    {
        $modules = $request->get('modules', []);
        $reportTitle = $request->get('report_title', 'LAPORAN PROGRAM KERJA');
        $reportSubtitle = $request->get('report_subtitle');
        
        $unitId = session('unit_id');
        $schoolYearId = session('school_year_id');
        $unit = $unitId ? Unit::find($unitId) : null;
        $schoolYear = SchoolYear::find($schoolYearId);

        $bundle = [];

        // 1. Cover
        if (in_array('cover', $modules)) {
            $bundle[] = [
                'type' => 'cover',
                'content' => view('reports.parts.cover', [
                    'report_title' => $reportTitle,
                    'report_subtitle' => $reportSubtitle,
                    'unit' => $unit,
                    'schoolYear' => $schoolYear
                ])->render()
            ];
        }

        // 2. Quality (Move to after Cover as requested)
        if (in_array('quality', $modules)) {
            $data = $this->getQualityReportData();
            $bundle[] = [
                'type' => 'quality',
                'content' => view('reports.quality_report', $data)->render()
            ];
        }

        // 3. Overview
        if (in_array('overview', $modules)) {
            $request->merge(['type' => 'overview']);
            $data = $this->getUnitReportData($request);
            $bundle[] = [
                'type' => 'overview',
                'content' => view('reports.overview_report', $data)->render()
            ];
        }

        // 4. Detailed
        if (in_array('detailed', $modules)) {
            $request->merge(['type' => 'detailed']);
            $data = $this->getUnitReportData($request);
            $bundle[] = [
                'type' => 'detailed',
                'content' => view('reports.unit_report', $data)->render()
            ];
        }

        // 5. Budget
        if (in_array('budget', $modules)) {
            $data = $this->getBudgetReportData();
            $bundle[] = [
                'type' => 'budget',
                'content' => view('reports.budget_report', $data)->render()
            ];
        }

        $pdf = Pdf::loadView('reports.bundle', compact('bundle'));
        $pdf->setPaper('a4', 'portrait');

        $filename = 'Bundle_Laporan_' . ($unit ? str_replace(' ', '_', $unit->name) : 'Semua_Unit') . '_' . date('Ymd_His') . '.pdf';
        
        return $pdf->stream($filename);
    }
}
