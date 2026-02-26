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
        Schema::table('strategic_plans', function (Blueprint $table) {
            $table->foreignId('unit_id')->nullable()->constrained('units')->onDelete('cascade');
            $table->foreignId('school_year_id')->nullable()->constrained('school_years')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('strategic_plans', function (Blueprint $table) {
            $table->dropForeign(['unit_id']);
            $table->dropForeign(['school_year_id']);
            $table->dropColumn(['unit_id', 'school_year_id']);
        });
    }
};
