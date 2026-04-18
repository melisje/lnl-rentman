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
        $app = Application::where('name', 'project_dashboard')->first();

        $account = 'llstageservice';
        $projects = $app->projects($account)
            ->orderBy('usageperiod_start')
            ->where('displayName', 'like', '%TEST%PROJECT%')
            // ->where('usageperiod_start', '<=', now()->addWeeks(4))
            // ->where('usageperiod_start', '>=', now())
            ->where('usageperiod_start', '>=', now()->subWeeks(2))
            ->get();

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
