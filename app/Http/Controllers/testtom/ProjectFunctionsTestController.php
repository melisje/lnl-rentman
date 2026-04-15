<?php

namespace App\Http\Controllers\testtom;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProjectFunctionsTestController extends Controller
{
    public function search(Request $request)
    {
        $search = $request->input('search');

        $projects = Project::orderBy('number')
            ->when($search, fn($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('number', 'like', "%{$search}%"))
            ->paginate(15)
            ->withQueryString()
            ->through(fn($project) => [
                'id'      => $project->id,
                'number'  => $project->number,
                'name'    => $project->name,
                'account' => $project->account,
                'url'     => route('testtom.projectfunctions.index', $project),
            ]);

        //return view('testtom.search', compact('projects', 'search'));

        return Inertia::render('Testtom/Search', [
            'projects'    => $projects,
            'search'    => $search,
            'indexUrl' => route('testtom.index'),
        ]);
    }

    public function overview(Request $request)
    {
        $thisWeekStart = now()->startOfWeek();
        $thisWeekEnd   = now()->endOfWeek();
        $nextWeekStart = now()->addWeek()->startOfWeek();
        $nextWeekEnd   = now()->addWeek()->endOfWeek();

        $thisWeekProjects = Project::orderBy('planperiod_start')
            ->select('rm_projects.*')
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(JSON_UNQUOTE(custom),'$.custom_33')) AS productie") //Dit moet uit een custom veld komen uit DB
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(JSON_UNQUOTE(custom),'$.custom_33')) = ?", ['1']) //Filter uit producties
            //->where('account', session('current_account')) //Gaan we nadien globaal aanpakken
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
            ->select('rm_projects.*')
            ->selectRaw("JSON_UNQUOTE(JSON_EXTRACT(JSON_UNQUOTE(custom),'$.custom_33')) AS productie") //Dit moet uit een custom veld komen uit DB
            ->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(JSON_UNQUOTE(custom),'$.custom_33')) = ?", ['1']) //Filter uit producties
            //->where('account', session('current_account')) //Gaan we nadien globaal aanpakken
            ->where(function ($q) use ($nextWeekStart, $nextWeekEnd) {
                $q->whereBetween('planperiod_start', [$nextWeekStart, $nextWeekEnd])
                    ->orWhereBetween('planperiod_end', [$nextWeekStart, $nextWeekEnd])
                    ->orWhere(function ($q) use ($nextWeekStart, $nextWeekEnd) {
                        $q->where('planperiod_start', '<=', $nextWeekStart)
                            ->where('planperiod_end', '>=', $nextWeekEnd);
                    });
            })
            ->get();

        $mapProject = fn($project) => [
            'id'              => $project->id,
            'number'          => $project->number,
            'name'            => $project->name,
            'url'             => route('testtom.projectfunctions.index', $project),
            'planperiod_start' => $project->planperiod_start?->format('d/m'),
            'planperiod_end'   => $project->planperiod_end?->format('d/m'),
        ];

        return Inertia::render('Testtom/Index', [
            'thisWeekLabel'    => $thisWeekStart->format('d/m') . ' – ' . $thisWeekEnd->format('d/m'),
            'nextWeekLabel'    => $nextWeekStart->format('d/m') . ' – ' . $nextWeekEnd->format('d/m'),
            'thisWeekProjects' => $thisWeekProjects->map($mapProject),
            'nextWeekProjects' => $nextWeekProjects->map($mapProject),
        ]);
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
