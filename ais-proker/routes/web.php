<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

Route::get('/login', [LoginController::class , 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class , 'login']);
Route::post('/logout', [LoginController::class , 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\DashboardController::class , 'index'])->name('dashboard');
    Route::get('/strategic', [App\Http\Controllers\StrategicController::class , 'index'])->name('strategic.index');
    Route::post('/strategic', [App\Http\Controllers\StrategicController::class , 'store'])->name('strategic.store');
    Route::put('/strategic/{id}', [App\Http\Controllers\StrategicController::class , 'update'])->name('strategic.update');
    Route::delete('/strategic/{id}', [App\Http\Controllers\StrategicController::class , 'destroy'])->name('strategic.destroy');
    Route::get('/main-programs', [App\Http\Controllers\WorkProgramController::class , 'mainPrograms'])->name('work-programs.main');
    Route::resource('/work-programs', App\Http\Controllers\WorkProgramController::class)->only(['index', 'show', 'edit', 'update', 'destroy', 'store']);
    Route::post('/work-programs/{id}/progress', [App\Http\Controllers\WorkProgramController::class , 'updateProgress'])->name('work-programs.update-progress');
    Route::post('/work-programs/{id}/indicators', [App\Http\Controllers\WorkProgramController::class , 'storeIndicator'])->name('work-programs.indicators.store');
    Route::put('/work-programs/{id}/indicators/{indicatorId}', [App\Http\Controllers\WorkProgramController::class , 'updateIndicator'])->name('work-programs.indicators.update');
    Route::delete('/work-programs/{id}/indicators/{indicatorId}', [App\Http\Controllers\WorkProgramController::class , 'deleteIndicator'])->name('work-programs.indicators.destroy');
    Route::post('/work-programs/{id}/indicators/{indicatorId}/achievement', [App\Http\Controllers\WorkProgramController::class , 'updateIndicatorAchievement'])->name('work-programs.indicators.achievement');
    Route::post('/work-programs/{id}/realization', [App\Http\Controllers\WorkProgramController::class , 'updateRealization'])->name('work-programs.update-realization');
    Route::post('/work-programs/{id}/sop', [App\Http\Controllers\WorkProgramController::class , 'uploadSop'])->name('work-programs.upload-sop');
    Route::delete('/work-programs/{id}/sop', [App\Http\Controllers\WorkProgramController::class , 'deleteSop'])->name('work-programs.delete-sop');
    Route::resource('/budget', App\Http\Controllers\BudgetController::class);
    Route::resource('/quality-targets', App\Http\Controllers\QualityTargetController::class);

    // Administrative Management
    Route::resource('/units', App\Http\Controllers\UnitController::class)->except(['create', 'show', 'edit']);
    Route::resource('/users', App\Http\Controllers\UserController::class)->except(['create', 'show', 'edit']);
    Route::resource('/parent-programs', App\Http\Controllers\ParentProgramController::class)->except(['create', 'show', 'edit']);
    
    Route::get('/export-report', [App\Http\Controllers\ReportController::class, 'exportUnitReport'])->name('export.report');
    Route::get('/export-quality', [App\Http\Controllers\ReportController::class, 'exportQualityReport'])->name('export.quality');
    Route::get('/export-budget', [App\Http\Controllers\ReportController::class, 'exportBudgetReport'])->name('export.budget');
    
    Route::get('/reports', [App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
    Route::post('/reports/bundle', [App\Http\Controllers\ReportController::class, 'exportBundle'])->name('reports.bundle');

    Route::post('/select-year', [App\Http\Controllers\AcademicYearController::class, 'setYear'])->name('select-year');
    Route::post('/select-unit', [App\Http\Controllers\AcademicYearController::class, 'setUnit'])->name('select-unit');
});
