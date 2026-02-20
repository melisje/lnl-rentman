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
    public function index(Request $request)
    {
        $search = $request->input('search');

        $projects = Project::query()
            ->when($search, function ($query, $search) {
                $query->where('name', 'LIKE', "%{$search}%");
            })
            ->orderBy('name', 'desc')
            ->paginate(15)
            ->withQueryString(); // Zorgt dat de filter bewaard blijft bij het bladeren door pagina's

        return view('rentman.project.index', compact('projects', 'search'));
    }

    public function show(Project $project)
    {
        return $project;
        $project = Project::findOrFail($id);
        $account = Account::find($project->account_id);
        $customFields = CustomField::where('account_id', $project->account_id)->get();

        return view('rentman.project.show', compact('project', 'account', 'customFields'));
    }

}
