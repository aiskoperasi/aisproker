<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\WorkProgram;

// Identify redundant entries: 
// 1. description is empty OR matches parent_name (ignoring case)
// 2. Indicators/PJ/Timeline might be empty as well
$programs = WorkProgram::all();
$deletedCount = 0;

foreach ($programs as $p) {
    $parentNormalized = strtoupper(trim($p->parent_name));
    $descNormalized = strtoupper(trim($p->description));

    // Logic: if description is empty or exactly matches parent_name title, it's likely a header row
    if (empty($p->description) || $parentNormalized === $descNormalized) {
        // Double check if it has any realization or budget that might be important
        if ($p->realization == 0 && empty($p->notes)) {
            echo "Deleting redundant entry: ID {$p->id} | Title: {$p->parent_name}\n";
            $p->delete();
            $deletedCount++;
        }
    }
}

echo "\nCleanup finished. Total deleted redundant entries: $deletedCount\n";
