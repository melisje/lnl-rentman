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
use Inertia\Inertia;

class TestJefController extends Controller
{
    //Nodig met map om te vertalen naar een array omdat Inertia problemen heeft met Collections die een Scope hebben
    public function weekoverzicht()
    {
        $app = Application::where('name', 'project_dashboard')->first();

        $projects = $app->projects('llstageservice')
            ->orderBy('planperiod_start')
            ->where('planperiod_start', '<=', now()->addWeeks(4))
            ->where('planperiod_start', '>=', now()->subWeeks(2))
            ->get()
            ->filter(fn($p) => $p->calculatedStatus !== 'Geannuleerd')
            ->map(fn($p) => [
                'id'                              => $p->id,
                'days_until_start_plan'           => $p->days_until_start_plan,
                'weeks_until_start_plan'          => $p->weeks_until_start_plan,
                'project_type_name'               => $p->projectType->name ?? $p->project_type,
                'account'                         => $p->account,
                'rm_id'                           => $p->rm_id,
                'planperiod_start'                => $p->planperiod_start?->toDateString(),
                'usageperiod_start'               => $p->usageperiod_start->toDateString(),
                'full_display_name'               => $p->full_display_name,
                'am_name'                         => $p->am_name,
                'pm_name'                         => $p->pm_name,
                'nr_of_subprojects'               => $p->nr_of_subprojects,
                'calculated_status'               => $p->calculated_status,
                'budget_consumption'              => $p->budget_consumption,
                'budgets'                         => $p->budgets,
                'count_checklist_items'           => $p->count_checklist_items,
                'count_checklist_items_completed' => $p->count_checklist_items_completed,
            ]);

        return Inertia::render('Testtom/Weekoverzicht', [
            'models' => $projects->values(),
        ]);
    }

    public function test1()
    {
        $app = Application::where('name', 'project_dashboard')->first();

        $account = 'llstageservice';
        $projects = $app->projects($account)
            ->orderBy('planperiod_start')
            // ->where('displayName', 'like', '%TEST%PROJECT%')
            ->where('planperiod_start', '<=', now()->addWeeks(4))
            ->where('planperiod_start', '>=', now()->subWeeks(2))
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
