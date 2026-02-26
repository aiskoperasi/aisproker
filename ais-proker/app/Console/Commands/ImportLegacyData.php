<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportLegacyData extends Command
{
    protected $signature = 'app:import-legacy-data';
    protected $description = 'Import data from legacy Excel file';

    public function handle()
    {
        $legacyDir = base_path('legacy');
        $files = glob($legacyDir . DIRECTORY_SEPARATOR . '*.xlsx');
        if (empty($files)) { $this->error("No .xlsx in: $legacyDir"); return; }
        usort($files, fn($a, $b) => filemtime($b) <=> filemtime($a));
        $filePath = $files[0];
        $this->info("Importing from: $filePath");

        try {
            DB::statement('SET FOREIGN_KEY_CHECKS=0');
            DB::table('work_program_updates')->truncate();
            \App\Models\WorkProgram::truncate();
            \App\Models\BudgetPlan::truncate();
            \App\Models\StrategicPlan::truncate();
            \App\Models\QualityTarget::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            $allSheets = \Maatwebsite\Excel\Facades\Excel::toArray([], $filePath, null, \Maatwebsite\Excel\Excel::XLSX);
            $this->info('Found ' . count($allSheets) . ' sheets:');
            foreach ($allSheets as $idx => $sheet) {
                $preview = implode(' | ', array_filter(array_slice($sheet[0] ?? [], 0, 4)));
                $this->line("  [{$idx}] rows=" . count($sheet) . " | " . substr($preview, 0, 80));
            }

            // SHEET MAP: [0]Users [1]Visi [2]Misi [3]Sasaran [4]Jabatan [5]Program [6]APBS

            // -- Visi (sheet 1)
            $visiData = $allSheets[1] ?? [];
            if (!empty($visiData[0][0])) {
                \App\Models\StrategicPlan::create(['type' => 'visi', 'content' => trim((string)$visiData[0][0])]);
                $this->info('Visi: imported');
            }

            // -- Misi (sheet 2)
            $misiData = $allSheets[2] ?? [];
            $n = 0;
            foreach ($misiData as $row) {
                $c = trim((string)($row[0] ?? ''));
                if (empty($c) || str_starts_with($c, '=')) continue;
                \App\Models\StrategicPlan::create(['type' => 'misi', 'content' => $c]);
                $n++;
            }
            $this->info("Misi: $n imported");

            // -- Sasaran Mutu (sheet 3)
            $sasaranData = $allSheets[3] ?? [];
            array_shift($sasaranData);
            $n = 0;
            foreach ($sasaranData as $row) {
                $s = trim((string)($row[1] ?? ''));
                if (empty($s) || str_starts_with($s, '=')) continue;
                \App\Models\QualityTarget::create([
                    'sasaran' => $s,
                    'target'  => trim((string)($row[2] ?? '')),
                    'periode' => trim((string)($row[3] ?? '')),
                    'metode'  => trim((string)($row[4] ?? '')),
                ]);
                $n++;
            }
            $this->info("Sasaran Mutu: $n imported");

            // -- Program Kerja (sheet 5)
            $programData = $allSheets[5] ?? [];
            $this->info('Program header: ' . implode('|', array_slice($programData[0] ?? [], 0, 6)));
            array_shift($programData);
            $n = 0;
            foreach ($programData as $row) {
                $id    = trim((string)($row[0] ?? ''));
                $induk = trim((string)($row[1] ?? ''));
                $sub   = trim((string)($row[2] ?? ''));
                $tipe  = strtolower(trim((string)($row[3] ?? '')));
                $pid   = trim((string)($row[4] ?? ''));
                $pj    = trim((string)($row[5] ?? ''));
                $unit  = trim((string)($row[6] ?? ''));
                $time  = trim((string)($row[7] ?? ''));
                $ind   = trim((string)($row[8] ?? ''));
                $ang   = $this->parseNum($row[9] ?? 0);
                $cat   = trim((string)($row[10] ?? ''));
                if (empty($id) || str_starts_with($id, '=')) continue;
                if (empty($induk) && empty($sub)) continue;
                $name = ($tipe === 'induk') ? ($induk ?: $sub) : ($sub ?: $induk);
                $desc = ($tipe === 'induk') ? $sub : $cat;

                \App\Models\WorkProgram::create([
                    'name'        => $name,
                    'parent_name' => $induk,
                    'description' => $desc,
                    'pj'          => $pj,
                    'unit'        => $unit,
                    'timeline'    => $time,
                    'indicators'  => $ind,
                    'budget'      => $ang,
                    'realization' => 0,
                    'notes'       => $id . ($tipe !== 'induk' && $pid ? " [sub:$pid]" : '') . ($cat ? " $cat" : ''),
                    'progress'    => 0,
                    'status'      => 'planning',
                ]);
                $n++;
            }
            $this->info("Program Kerja: $n imported");

            // -- APBS (sheet 6)
            $apbsData = $allSheets[6] ?? [];
            $this->info('APBS header: ' . implode('|', array_slice($apbsData[0] ?? [], 0, 5)));
            array_shift($apbsData);
            $n = 0;
            foreach ($apbsData as $row) {
                $kode = trim((string)($row[1] ?? ''));
                $uri  = trim((string)($row[2] ?? ''));
                $amt  = $this->parseNum($row[3] ?? 0);
                $ket  = trim((string)($row[4] ?? ''));
                if ((empty($kode) && empty($uri)) || str_starts_with($kode, '=')) continue;
                if ($amt == 0 && empty($kode)) continue;
                \App\Models\BudgetPlan::create([
                    'code'        => $kode,
                    'description' => $uri,
                    'amount'      => $amt,
                    'realization' => 0,
                    'notes'       => $ket,
                ]);
                $n++;
            }
            $this->info("APBS: $n imported");
            $this->info('==> DONE!');

        } catch (\Exception $e) {
            $this->error('Error: ' . $e->getMessage());
            \Log::error($e);
        }
    }

    private function parseNum($v): float
    {
        if (is_numeric($v)) return (float)$v;
        $str = trim((string)$v);
        if (empty($str)) return 0;

        // If it looks like a formula (starts with =)
        if (strlen($str) > 0 && $str[0] === '=') {
            $formula = substr($str, 1);
            if (str_contains($formula, '+')) {
                $parts = explode('+', $formula);
                $sum = 0;
                foreach ($parts as $part) {
                    $sum += $this->parseNum($part);
                }
                return $sum;
            }
            if (preg_match('/(\d+)/', $formula, $matches)) {
                return (float)$matches[1];
            }
        }

        $clean = preg_replace('/[^\d.]/', '', str_replace(',', '.', $str));
        return (float)$clean;
    }
}