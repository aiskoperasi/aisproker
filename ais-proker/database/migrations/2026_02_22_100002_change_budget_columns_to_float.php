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
        Schema::table('budget_plans', function (Blueprint $table) {
            $table->double('amount')->change();
            $table->double('realization')->change();
        });

        Schema::table('work_programs', function (Blueprint $table) {
            $table->double('budget')->change();
            $table->double('realization')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('budget_plans', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
            $table->decimal('realization', 15, 2)->change();
        });

        Schema::table('work_programs', function (Blueprint $table) {
            $table->decimal('budget', 15, 2)->change();
            $table->decimal('realization', 15, 2)->change();
        });
    }
};
