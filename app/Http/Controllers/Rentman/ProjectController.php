<?php

namespace App\Http\Controllers\Rentman;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Project;
use App\Services\RentmanApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class ProjectController extends Controller
{
    protected RentmanApiService $rentmanApi;

    /**
     * Dependency Injection (DI) via constructor.
     * Laravel's Service Container create a RentmanApiService instance
     * automatically and passes to this controller instance.
     */
    public function __construct(RentmanApiService $rentmanApi)
    {
        $this->rentmanApi = $rentmanApi;
    }

    public function fetch(Request $request)
    {
        $validatedData = collect();
        $formData = collect();

        if($request->isMethod('GET'))
        {

            $test = collect(
                [
                    'filter'=> http_build_query(['modified[gte]'=> '2025-11-20','number'=>33],'idx'),
                    'projects' => $this->rentmanApi->getEndpointData('projects',['modified[gte]'=>'2025-10-21','limit'=>50, 'fields' => 'displayname,number',]),
                    'invoices' => $this->rentmanApi->getEndpointData('invoices',['modified[gte]'=>'2025-10-21','limit'=>50, 'fields' => 'displayname,number']),
                    'proj8245' => $this->rentmanApi->getEndpointData('projects/8444',['fields' => '']),
                    // 'subprojects' => $this->rentmanApi->getEndpointData('projects/8444/subprojects',['modified[gte]'=>'2025-10-21','limit'=>50, 'fields' => 'displayname,number']),

                ]
            );

            if (!empty($test[ 'proj8245']))
            {
                $project = $test['proj8245'][0];

                // dd($project);

                $validatedData = ['number' => 'xxxx'];
                return view('rentman.project.detail', compact('formData', 'validatedData', 'test', 'project'));
            }


            return $test;
        }

        if ($request->isMethod('POST'))
        {
            $validatedData = $request->validate(
            [
                'number' => 'required|string'
            ]);


            // fetch project
            $queryParams = [
                'filter' => '',
                'offset' => 0,
                'filter' => 'number=' . $validatedData['number']
            ];
            $endpoint1 = 'projects' ;

            $project =  collect(ApiConctroller::fetch($endpoint1, $queryParams)[0]);

            // Fetch subproject
            $queryParams = [
                'limit' => 300,
                'filter' => '',
                'offset' => 0,
            ];

            $projectId = $project['id'];
            $endpoint2 = $endpoint1 ."/". $projectId .   "/subprojects";
            $subprojects =  collect(ApiConctroller::fetch($endpoint2, $queryParams));

            return view('rentman.project.detail', compact('formData', 'validatedData','project', 'subprojects'));
        }



    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Project::paginate(25);

        return view('rentman.project.index',compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return 'CREATE';
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        return 'STORE';
    }

    /**
     * Display the specified resource.
     */
    // public function show(string $id)
    public function show(Request $request, Project $project)
    {
        return view('rentman.project.show',compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return 'EDIT';
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return 'UPDATE';
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return 'DESTROY';
    }

}
