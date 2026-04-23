<?php

namespace App\Http\Controllers\testtom;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Account;
use App\Models\Rentman\Application;
use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectType;
use App\Models\Rentman\SubProject;
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
            ->orderBy('planperiod_start')
            // ->where('displayName', 'like', '%TEST%PROJECT%')
            ->where('usageperiod_start', '<=', now()->addWeeks(4))
            // ->where('usageperiod_start', '>=', now())
            ->where('usageperiod_start', '>=', now()->subWeeks(2))
            ->get()
            // filter only projects that are not cancelled
            ->filter(
                function ($project)
                {
                    // Behoud alleen projecten die NIET de status "geannulleerd" hebben
                    return $project->calculatedStatus !== 'Geannuleerd';
                });

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

    public function test3()
    {
        // Find the project that we are working on
        $app = Application::where('name','warehouse_dashboard')->first();
        // dump($app);

        $builder = SubProject::with('parentProject')
            // ->releasedForWarehouse()
            ->notCancelled()
            ->whereDate('planperiod_start', '>=',today())
            ->orderBy('planperiod_start')
        ;

        dump($builder->toRawSql());
        $subProjects = $builder->get();

        return view('testtom.project.budget.test3', [
            'models' => $subProjects,
        ]);
    }

}
