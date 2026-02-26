<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Unit;
use App\Models\SchoolYear;

class InitialSystemDataSeeder extends Seeder
{
    public function run(): void
    {
        // 1. School Years
        $years = [
            ['name' => '2024-2025', 'is_active' => false],
            ['name' => '2025-2026', 'is_active' => true],
            ['name' => '2026-2027', 'is_active' => false],
            ['name' => '2027-2028', 'is_active' => false],
        ];

        foreach ($years as $year) {
            SchoolYear::updateOrCreate(['name' => $year['name']], $year);
        }

        // 2. Units (Example from Layanan Umum patterns)
        $units = [
            ['name' => 'SD', 'type' => 'KBM'],
            ['name' => 'SMP', 'type' => 'KBM'],
            ['name' => 'SMA', 'type' => 'KBM'],
            ['name' => 'TK', 'type' => 'KBM'],
            ['name' => 'Sarpras', 'type' => 'Supporting'],
            ['name' => 'IT', 'type' => 'Supporting'],
            ['name' => 'Security', 'type' => 'Supporting'],
            ['name' => 'OB', 'type' => 'Supporting'],
            ['name' => 'Admin', 'type' => 'Supporting'],
        ];

        foreach ($units as $unit) {
            Unit::updateOrCreate(['name' => $unit['name']], $unit);
        }
    }
}
