<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QualityTargetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
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

        $programs = \App\Models\WorkProgram::all();
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

        // Map targets to program progress
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
                // Avg of Program 4, 5, 6
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

            // Sync achievement from Work Programs (always dynamic for listing)
            $target->achievement = round($newAchievement, 2);
        }

        return view('quality-targets.index', compact('targets'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'sasaran' => 'required|string',
            'target' => 'required|numeric|min:0|max:100',
            'achievement' => 'nullable|numeric|min:0|max:100',
            'periode' => 'required|string',
            'metode' => 'required|string',
        ]);

        $data = array_merge($validated, [
            'unit_id' => session('unit_id') ?? auth()->user()->unit_id,
            'school_year_id' => session('school_year_id'),
            'achievement' => $request->achievement ?? 0,
        ]);

        \App\Models\QualityTarget::create($data);

        return redirect()->route('quality-targets.index')->with('success', 'Sasaran mutu berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $target = \App\Models\QualityTarget::findOrFail($id);
        return response()->json($target);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'sasaran' => 'required|string',
            'target' => 'required|numeric|min:0|max:100',
            'achievement' => 'required|numeric|min:0|max:100',
            'periode' => 'required|string',
            'metode' => 'required|string',
        ]);

        $target = \App\Models\QualityTarget::findOrFail($id);
        $target->update($validated);

        return redirect()->route('quality-targets.index')->with('success', 'Sasaran mutu berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $target = \App\Models\QualityTarget::findOrFail($id);
        $target->delete();

        return redirect()->route('quality-targets.index')->with('success', 'Sasaran mutu berhasil dihapus.');
    }
}
