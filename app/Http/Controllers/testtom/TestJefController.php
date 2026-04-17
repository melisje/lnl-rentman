<?php

namespace App\Http\Controllers\testtom;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Account;
use App\Models\Rentman\Application;
use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectType;
use App\Scopes\AccountScope;
use Illuminate\Support\Facades\DB;

class TestJefController extends Controller
{
    //
    public function test1()
    {
        $projects = Project::orderBy('usageperiod_start')
            ->where('displayName', 'like', '%TEST%PROJECT%')
            ->where('usageperiod_start', '<', now()->addWeeks(4))
            // ->where('usageperiod_start', '>', now())
            ->get()
            // ->sortBy('weeks_until_start') // Sorteert oplopend (0, 1, 2, 3...)
            ;

        return view('testtom.project.budget.test1', [
            'models' => $projects,
        ]);


    }

    /**
     * Find projects mapped to a specific appliction
     *
     */
    public function test2()
    {
        // Find the project that we are working on
        $app = Application::first();


        // Find all projects that are related to this application based on the
        // table rm_project_type_application_mapping.

        $accounts = ['llstageservice', 'ledvisions'];
        foreach ($accounts as $account)
        {
            $result = $app->projects($account)->count();
            dump("Account: $account, project count: $result");
        }

    }

}
