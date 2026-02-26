<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::table('work_programs', function (Blueprint $table) {
            $table->tinyInteger('progress')->default(0)->after('realization');
            $table->enum('status', ['planning', 'on_progress', 'done', 'cancelled'])->default('planning')->after('progress');
            $table->string('sop_file')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('work_programs', function (Blueprint $table) {
            $table->dropColumn(['progress', 'status', 'sop_file']);
        });
    }
};
