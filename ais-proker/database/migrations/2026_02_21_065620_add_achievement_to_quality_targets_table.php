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
        Schema::table('quality_targets', function (Blueprint $table) {
            $table->decimal('achievement', 5, 2)->default(0)->after('target');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quality_targets', function (Blueprint $table) {
            $table->dropColumn('achievement');
        });
    }
};
