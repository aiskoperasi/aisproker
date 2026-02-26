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
        Schema::create('work_program_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_program_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('weight', 5, 2)->default(0); // Bobot dalam persen (0-100)
            $table->text('target')->nullable();
            $table->decimal('achievement', 5, 2)->default(0); // Capaian dalam persen (0-100)
            $table->string('evidence_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_program_indicators');
    }
};
