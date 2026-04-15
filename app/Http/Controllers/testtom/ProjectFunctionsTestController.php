<?php

namespace App\Http\Controllers\testtom;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Project;
use Illuminate\Http\Request;

class ProjectFunctionsTestController extends Controller
{
    public function overview(Request $request)
    {
        $search = $request->input('search');

        $thisWeekStart = now()->startOfWeek();
        $thisWeekEnd   = now()->endOfWeek();
        $nextWeekStart = now()->addWeek()->startOfWeek();
        $nextWeekEnd   = now()->addWeek()->endOfWeek();

        $thisWeekProjects = Project::orderBy('planperiod_start')
            ->where(function ($q) use ($thisWeekStart, $thisWeekEnd) {
                $q->whereBetween('planperiod_start', [$thisWeekStart, $thisWeekEnd])
                  ->orWhereBetween('planperiod_end', [$thisWeekStart, $thisWeekEnd])
                  ->orWhere(function ($q) use ($thisWeekStart, $thisWeekEnd) {
                      $q->where('planperiod_start', '<=', $thisWeekStart)
                        ->where('planperiod_end', '>=', $thisWeekEnd);
                  });
            })
            ->get();

        $nextWeekProjects = Project::orderBy('planperiod_start')
            ->where(function ($q) use ($nextWeekStart, $nextWeekEnd) {
                $q->whereBetween('planperiod_start', [$nextWeekStart, $nextWeekEnd])
                  ->orWhereBetween('planperiod_end', [$nextWeekStart, $nextWeekEnd])
                  ->orWhere(function ($q) use ($nextWeekStart, $nextWeekEnd) {
                      $q->where('planperiod_start', '<=', $nextWeekStart)
                        ->where('planperiod_end', '>=', $nextWeekEnd);
                  });
            })
            ->get();

        $projects = Project::orderBy('number')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                                        ->orWhere('number', 'like', "%{$search}%"))
            ->paginate(15)
            ->withQueryString();

        return view('testtom.index', compact('projects', 'search', 'thisWeekProjects', 'nextWeekProjects'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        $projectfunctions = $project->projectFunctions;
        return view('testtom.project.subproject.projectfunction.index', compact('project', 'projectfunctions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
