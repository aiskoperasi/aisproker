<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('work_programs', function (Blueprint $table) {
            $table->foreignId('parent_program_id')->nullable()->after('id')->constrained('parent_programs')->onDelete('set null');
        });

        // Data Migration: Move existing parent_name to parent_programs
        $existingParents = DB::table('work_programs')
            ->select('parent_name', 'unit_id', 'school_year_id')
            ->whereNotNull('parent_name')
            ->distinct()
            ->get();

        foreach ($existingParents as $parent) {
            // Check if already exists to avoid duplicates
            $existingId = DB::table('parent_programs')
                ->where('name', $parent->parent_name)
                ->where('unit_id', $parent->unit_id)
                ->where('school_year_id', $parent->school_year_id)
                ->value('id');

            if (!$existingId) {
                $existingId = DB::table('parent_programs')->insertGetId([
                    'name' => $parent->parent_name,
                    'unit_id' => $parent->unit_id,
                    'school_year_id' => $parent->school_year_id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            DB::table('work_programs')
                ->where('parent_name', $parent->parent_name)
                ->where('unit_id', $parent->unit_id)
                ->where('school_year_id', $parent->school_year_id)
                ->update(['parent_program_id' => $existingId]);
        }
    }

    public function down(): void
    {
        Schema::table('work_programs', function (Blueprint $table) {
            $table->dropForeign(['parent_program_id']);
            $table->dropColumn('parent_program_id');
        });
    }
};
