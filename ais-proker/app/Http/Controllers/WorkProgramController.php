<?php

namespace App\Http\Controllers;

use App\Models\WorkProgram;
use App\Models\WorkProgramUpdate;
use App\Models\ParentProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WorkProgramController extends Controller
{
    public function mainPrograms()
    {
        $unitId = session('unit_id');
        $schoolYearId = session('school_year_id');

        $mainPrograms = ParentProgram::where('school_year_id', $schoolYearId)
            ->when($unitId, function ($query, $unitId) {
                return $query->where('unit_id', $unitId);
            })
            ->withCount(['workPrograms' => function ($query) use ($unitId, $schoolYearId) {
                $query->withoutGlobalScopes();
                if ($unitId) $query->where('unit_id', $unitId);
                $query->where('school_year_id', $schoolYearId);
            }])
            ->with(['workPrograms' => function ($query) use ($unitId, $schoolYearId) {
                $query->withoutGlobalScopes();
                if ($unitId) $query->where('unit_id', $unitId);
                $query->where('school_year_id', $schoolYearId);
            }])
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $mainPrograms->each(function ($parent) {
            $parent->average_progress = $parent->workPrograms->avg('progress') ?: 0;
            $parent->sub_count = $parent->workPrograms->count();
        });

        return view('work-programs.main_programs', compact('mainPrograms'));
    }

    public function index(Request $request)
    {
        $query = WorkProgram::withCount('updates');

        if ($request->filled('parent_program_id')) {
            $query->where('parent_program_id', $request->parent_program_id);
        }
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs = $query->get();

        $allParentPrograms = ParentProgram::where('school_year_id', session('school_year_id'));
        if (session('unit_id')) {
            $allParentPrograms->where('unit_id', session('unit_id'));
        }
        $allParentPrograms = $allParentPrograms->get();

        $allStatuses = ['planning', 'on_progress', 'done', 'cancelled'];

        $stats = [
            'total' => $programs->count(),
            'done' => $programs->where('status', 'done')->count(),
            'on_progress' => $programs->where('status', 'on_progress')->count(),
            'planning' => $programs->where('status', 'planning')->count(),
            'avg_progress' => $programs->count() ? round($programs->avg('progress')) : 0,
            'total_budget' => $programs->sum('budget'),
        ];

        return view('work-programs.index', compact('programs', 'stats', 'allParentPrograms', 'allStatuses'));
    }

    public function show($id)
    {
        $program = WorkProgram::with('updates')->findOrFail($id);
        return view('work-programs.show', compact('program'));
    }

    public function edit($id)
    {
        $program = WorkProgram::findOrFail($id);
        if (request()->ajax()) {
            return response()->json($program);
        }
        return view('work-programs.edit', compact('program'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_program_id' => 'required|exists:parent_programs,id',
            'description' => 'required|string',
            'pj' => 'nullable|string|max:255',
            'timeline' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required|in:planning,on_progress,done,cancelled',
        ]);

        $data = $request->except(['unit_id', 'school_year_id', 'realization']);
        $data['unit_id'] = session('unit_id') ?? auth()->user()->unit_id;
        $data['school_year_id'] = session('school_year_id') ?? \App\Models\SchoolYear::where('is_active', true)->first()->id;
        $data['realization'] = $request->realization ?? 0;
        
        // Sync parent_name for backward compatibility if needed
        $parent = ParentProgram::find($request->parent_program_id);
        $data['parent_name'] = $parent->name;

        WorkProgram::create($data);

        return redirect()->route('work-programs.index')->with('success', 'Program kerja berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_program_id' => 'required|exists:parent_programs,id',
            'description' => 'required|string',
            'pj' => 'nullable|string|max:255',
            'timeline' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required|in:planning,on_progress,done,cancelled',
        ]);

        $program = WorkProgram::findOrFail($id);
        $data = $request->all();
        
        $parent = ParentProgram::find($request->parent_program_id);
        $data['parent_name'] = $parent->name;

        $program->update($data);

        return redirect()->route('work-programs.index')->with('success', 'Program kerja berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $program = WorkProgram::findOrFail($id);
        $program->delete();
        return redirect()->route('work-programs.index')->with('success', 'Program kerja berhasil dihapus.');
    }

    public function updateProgress(Request $request, $id)
    {
        $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'required|in:planning,on_progress,done,cancelled',
            'note' => 'nullable|string'
        ]);

        $program = WorkProgram::findOrFail($id);
        
        WorkProgramUpdate::create([
            'work_program_id' => $program->id,
            'progress_before' => $program->progress,
            'progress_after' => $request->progress,
            'status_before' => $program->status,
            'status_after' => $request->status,
            'note' => $request->note
        ]);

        $program->update([
            'progress' => $request->progress,
            'status' => $request->status
        ]);

        return redirect()->route('work-programs.show', $id)
            ->with('success', 'Progress berhasil diperbarui.');
    }

    public function uploadSop(Request $request, $id)
    {
        $request->validate([
            'sop_file' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $program = WorkProgram::findOrFail($id);

        if ($program->sop_file && Storage::disk('public')->exists($program->sop_file)) {
            Storage::disk('public')->delete($program->sop_file);
        }

        $path = $request->file('sop_file')->store('sop_files', 'public');
        $program->update(['sop_file' => $path]);

        return redirect()->route('work-programs.show', $id)
            ->with('success', 'File SOP berhasil diunggah.');
    }

    public function deleteSop($id)
    {
        $program = WorkProgram::findOrFail($id);

        if ($program->sop_file && Storage::disk('public')->exists($program->sop_file)) {
            Storage::disk('public')->delete($program->sop_file);
        }

        $program->update(['sop_file' => null]);

        return redirect()->route('work-programs.show', $id)
            ->with('success', 'File SOP berhasil dihapus.');
    }

    public function updateRealization(Request $request, $id)
    {
        $request->validate([
            'realization' => 'required|numeric|min:0',
        ]);

        $program = WorkProgram::findOrFail($id);
        $program->update(['realization' => $request->realization]);

        return redirect()->route('work-programs.show', $id)
            ->with('success', 'Realisasi anggaran berhasil diperbarui!');
    }

    public function storeIndicator(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0|max:100',
            'target' => 'nullable|string',
        ]);

        $program = WorkProgram::findOrFail($id);

        // Optional: Check if total weight exceeds 100%
        $currentWeight = $program->weightedIndicators->sum('weight');
        if ($currentWeight + $request->weight > 100) {
            return back()->with('error', 'Total bobot tidak boleh melebihi 100%. Sisa kuota: ' . (100 - $currentWeight) . '%');
        }

        $program->weightedIndicators()->create($request->all());

        return redirect()->route('work-programs.show', $id)->with('success', 'Indikator berhasil ditambahkan.');
    }

    public function updateIndicator(Request $request, $id, $indicatorId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'weight' => 'required|numeric|min:0|max:100',
            'target' => 'nullable|string',
        ]);

        $indicator = \App\Models\WorkProgramIndicator::where('work_program_id', $id)->findOrFail($indicatorId);

        $currentWeight = \App\Models\WorkProgramIndicator::where('work_program_id', $id)
            ->where('id', '!=', $indicatorId)
            ->sum('weight');

        if ($currentWeight + $request->weight > 100) {
            return back()->with('error', 'Total bobot tidak boleh melebihi 100%.');
        }

        $indicator->update($request->all());

        return redirect()->route('work-programs.show', $id)->with('success', 'Indikator berhasil diperbarui.');
    }

    public function deleteIndicator($id, $indicatorId)
    {
        $indicator = \App\Models\WorkProgramIndicator::where('work_program_id', $id)->findOrFail($indicatorId);
        $indicator->delete();

        return redirect()->route('work-programs.show', $id)->with('success', 'Indikator berhasil dihapus.');
    }

    public function updateIndicatorAchievement(Request $request, $id, $indicatorId)
    {
        $request->validate([
            'achievement' => 'required|numeric|min:0|max:100',
            'note' => 'nullable|string',
            'evidence_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);

        $program = WorkProgram::findOrFail($id);
        $indicator = \App\Models\WorkProgramIndicator::where('work_program_id', $id)->findOrFail($indicatorId);

        $oldProgramProgress = $program->progress;
        $oldStatus = $program->status;

        $data = ['achievement' => $request->achievement];

        if ($request->hasFile('evidence_file')) {
            if ($indicator->evidence_file && Storage::disk('public')->exists($indicator->evidence_file)) {
                Storage::disk('public')->delete($indicator->evidence_file);
            }
            $data['evidence_file'] = $request->file('evidence_file')->store('evidence_files', 'public');
        }

        $indicator->update($data);

        // Refresh program status automatically if 100%
        $newProgress = $program->fresh()->progress;
        $newStatus = $program->status;
        if ($newProgress >= 100 && $oldStatus !== 'done') {
            $program->update(['status' => 'done']);
            $newStatus = 'done';
        } elseif ($newProgress > 0 && $oldStatus === 'planning') {
            $program->update(['status' => 'on_progress']);
            $newStatus = 'on_progress';
        }

        // Create update history
        WorkProgramUpdate::create([
            'work_program_id' => $program->id,
            'progress_before' => $oldProgramProgress,
            'progress_after' => $newProgress,
            'status_before' => $oldStatus,
            'status_after' => $newStatus,
            'note' => "[Indikator: {$indicator->name}] " . ($request->note ?? "Pembaruan capaian menjadi {$request->achievement}%"),
            'updated_by' => auth()->user()->name ?? 'System'
        ]);

        return redirect()->route('work-programs.show', $id)->with('success', 'Capaian indikator berhasil diperbarui.');
    }
}
