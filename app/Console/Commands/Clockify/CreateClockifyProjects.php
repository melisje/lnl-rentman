<?php

namespace App\Console\Commands\Clockify;

use App\Models\Rentman\Project;
use App\Services\Clockify\ClockifyApiService;
use Illuminate\Console\Command;
use Exception;

class CreateClockifyProjects extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'clockify:create-projects';

    /**
     * The console command description.
     */
    protected $description = 'Sync Rentman projects to Clockify with clean terminal output';

    /**
     * Execute the console command.
     */
    public function handle(ClockifyApiService $clockify): void
    {
        // 1. Fetch projects
        $projects = Project::where('usageperiod_start', '>=', '2026-03-10')
            ->orderBy('usageperiod_start', 'asc')
            // ->offset(5)
            // ->limit(100)
            ->get();

        if ($projects->isEmpty()) {
            $this->warn("No projects found matching the criteria.");
            return;
        }

        $this->info("Found {$projects->count()} projects. Starting sync...");
        $this->newLine();

        // Used to collect results for a final summary table
        $summary = [];

        foreach ($projects as $project) {
            try {
                // Attempt to create the project
                $result = $clockify->createProject($project);

                // Using direct color tags correctly: <info> is green, </> closes it.
                $this->line("<info>✔ SUCCESS</info> | Project: {$result['name']}");

                $summary[] = [$project->rm_id, $result['name'], 'Created'];

                // create tasks for this project
                $clockify_projectid = $result['id'];
                $clockify->addTaskToProject($clockify_projectid,"PM");
                $clockify->addTaskToProject($clockify_projectid,"Light");
                $clockify->addTaskToProject($clockify_projectid,"Sound");
                $clockify->addTaskToProject($clockify_projectid,"Rigging");


            } catch (Exception $e) {
                // <fg=red> is red text, </> closes it.
                $this->line("<fg=red>✘ FAILED </fg=red> | Project: {$project->displayname} | Error: {$e->getMessage()}");

                $summary[] = [$project->rm_id, $project->displayname, 'Error'];
            }
        }

        // 2. Display a final summary table
        $this->newLine();
        $this->info("Final Summary:");
        $this->table(
            ['Rentman ID', 'Project Name', 'Status'],
            $summary
        );
    }
}
