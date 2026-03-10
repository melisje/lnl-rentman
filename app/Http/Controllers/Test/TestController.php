<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use App\Models\Rentman\ProjectCrew;
use App\Models\Rentman\ProjectFunction;
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\ProjectsService;
use App\Services\Rentman\Api\RentmanApiService;
use Illuminate\Http\Request;

class TestController extends Controller
{
    protected $projectService;
    protected $rmapiservice;

    public function __construct(ProjectsService $projectService, RentmanApiService $rmapiservice)
    {
        $this->projectService = $projectService;
        $this->rmapiservice = $rmapiservice;
    }

    public function showTestForm()
    {
        return view('test.sync-crew');
    }

    public function runSync(Request $request)
    {
        $request->validate([
            'rm_id' => 'required|integer',
        ]);

        // get account
        $account = session('current_account',null);
        // dump($account);
        if (!$account) {
            return back()->withInput()->with('error', 'Account "' . $account . '" niet gevonden. . Selecteer eerst een account.');
        }

        // Zoek het subproject in onze eigen database op basis van rm_id
        $subproject = SubProject::where('account', $account)
            ->where('rm_id', $request->rm_id)
            ->first();

        if (!$subproject) {
            return back()->withInput()->with('error', 'SubProject niet gevonden in account "'. $account . '" in de lokale database. Sync eerst het subproject.');
        }

        try {
            // Fetch the subproject's functions
            $functions = $this->projectService->syncSubProjectFunctions($account, $subproject);
            dump($functions);

            $functionCount = ProjectFunction::where('account', $account)
                ->where('subproject', "/subprojects/{$subproject->rm_id}")
                ->count();

            // $crewCount = ProjectCrew::where('account', $account)
            //     ->where('subproject_id', $subproject->id) // Indien je deze relatie hebt
            //     ->count();

            // return back()->with('success', "Sync voltooid! Er staan nu $crewCount crew-items in de database voor dit subproject.");
            return view('test.sync-crew')
                // ->withInput()
                ->with('success', "Sync voltooid! Er staan nu $functionCount functions in de database voor dit subproject.")
                ->with('rm_id',$request->rm_id)
                ;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Fout tijdens sync: ' . $e->getMessage());
        }
    }
}
