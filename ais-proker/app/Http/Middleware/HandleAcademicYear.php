<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleAcademicYear
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // 1. Get active school year if not in session
        if (!$request->session()->has('school_year_id')) {
            $activeYear = \App\Models\SchoolYear::where('is_active', true)->first() 
                         ?? \App\Models\SchoolYear::latest()->first();
            
            if ($activeYear) {
                $request->session()->put('school_year_id', $activeYear->id);
                $request->session()->put('school_year_name', $activeYear->name);
            }
        }

        // 2. Automate unit session based on user profile
        if (auth()->check()) {
            $user = auth()->user();
            if ($user->unit_id) {
                // If user belongs to a unit, force their session to that unit
                $request->session()->put('unit_id', $user->unit_id);
                if (!$request->session()->has('unit_name')) {
                    $request->session()->put('unit_name', $user->unit->name ?? 'Unit');
                }
            }
        }

        // 2. Share data with all views
        $schoolYears = \App\Models\SchoolYear::orderBy('name', 'desc')->get();
        $units = \App\Models\Unit::orderBy('name', 'asc')->get();
        $activeYearId = $request->session()->get('school_year_id');
        $activeYear = $schoolYears->firstWhere('id', $activeYearId);

        view()->share('allSchoolYears', $schoolYears);
        view()->share('allUnits', $units);
        view()->share('activeSchoolYear', $activeYear);

        return $next($request);
    }
}
