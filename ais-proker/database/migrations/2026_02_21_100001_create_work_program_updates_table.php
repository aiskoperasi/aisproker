<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up(): void
    {
        Schema::create('work_program_updates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_program_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('progress_before')->default(0);
            $table->tinyInteger('progress_after')->default(0);
            $table->string('status_before')->nullable();
            $table->string('status_after')->nullable();
            $table->text('note')->nullable();
            $table->string('updated_by')->default('System');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('work_program_updates');
    }
};
