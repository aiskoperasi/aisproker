<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
        });

        Schema::table('work_programs', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('school_year_id')->nullable()->constrained('school_years')->nullOnDelete();
        });

        Schema::table('budget_plans', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('school_year_id')->nullable()->constrained('school_years')->nullOnDelete();
        });

        Schema::table('quality_targets', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete();
            $table->foreignId('school_year_id')->nullable()->constrained('school_years')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropColumn('unit_id');
        });

        Schema::table('work_programs', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['school_year_id']);
            $table->dropColumn(['unit_id', 'school_year_id']);
        });

        Schema::table('budget_plans', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['school_year_id']);
            $table->dropColumn(['unit_id', 'school_year_id']);
        });

        Schema::table('quality_targets', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['school_year_id']);
            $table->dropColumn(['unit_id', 'school_year_id']);
        });
    }
};
