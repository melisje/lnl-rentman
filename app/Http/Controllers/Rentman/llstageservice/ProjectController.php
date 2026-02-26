<?php

namespace App\Http\Controllers\Rentman\llstageservice;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Models\Rentman\CustomField;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display an overview of the projects
      *
      * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Fetch projects for current account, including their subprojects
        $projects = Project::where('account', session('current_account'))
            ->with('subprojects')
            ->get();

        // return project data to view ;
        return view('rentman.project.index', compact('projects'));
    }

    public function show(Project $project)
    {
        return $project;
        $project = Project::findOrFail($id);
        $account = Account::find($project->account_id);
        $customFields = CustomField::where('account_id', $project->account_id)->get();

        return view('rentman.project.show', compact('project', 'account', 'customFields', 'accountFilter'));
    }

}
