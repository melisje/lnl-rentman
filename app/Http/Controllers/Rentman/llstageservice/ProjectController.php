<?php

namespace App\Http\Controllers\Rentman\llstageservice;

use App\Http\Controllers\Controller;
use App\Models\Rentman\Account;
use App\Models\Rentman\Project;
use App\Models\Rentman\CustomField;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display projects for the llstageservice account.
     */
    public function index1(Account $account): View
    {
        /* Fetch the definition for the PM custom field */
        $pmField = CustomField::where('name', 'PM')
            ->first();

        /* Select only the required columns for performance */
        $projects = Project::query()
            ->select(
                [
                    'id',
                    'rm_id',
                    'account',
                    'rm_id',
                    'name',
                    'number',
                    'account_manager',
                    'planperiod_start',
                    'planperiod_end',
                    // 'calculated_status',
                    'custom'
                ]
            )

            ->where('account', 'llstageservice')
            ->orderBy('planperiod_start', 'desc')
            ->get();

        return view(
            'rentman.project.llstageservice.index',
            [
                'account' => $account,
                'projects' => $projects,
                'pmField' => $pmField
            ]
        );
    }
    /**
     * Display projects for the llstageservice account.
     */
    public function index(Account $account): View
    {
        // Ensure the account is for llstageservice
        // if ($account->name !== 'llstageservice') {
        //     abort(404, 'Account not found');
        // }

        // Retrieve projects for the llstageservice account
        $projects = Project::where('account', $account->account)
            ->where('rm_id', 8588)
            ->first();

        // Retrieve custom fields for the llstageservice account
        $customFields = CustomField::where('account', $account->account)->get();

        // Return the view with projects and custom fields
        return view('rentman.project.llstageservice.index', compact('projects', 'customFields'));
    }
}
