<?php

namespace App\Console\Commands\Clockify;

use App\Services\Clockify\ClockifyApiService;
use Illuminate\Console\Command;
use Exception;

class DeleteClockifyProjects extends Command
{
    protected $signature = 'clockify:delete-projects {--force : Skip the confirmation question}';
    protected $description = 'Delete ALL projects from the Clockify workspace';

    public function handle(ClockifyApiService $clockify): void
    {
        // 1. Get all projects first
        $projects = $clockify->getAllProjects();

        if (empty($projects)) {
            $this->info("No projects found to delete.");
            return;
        }

        $count = count($projects);

        // 2. Safety check: ask for confirmation unless --force is used
        if (!$this->option('force') && !$this->confirm("Are you sure you want to delete ALL {$count} projects? This cannot be undone!")) {
            $this->warn("Operation cancelled.");
            return;
        }

        $this->info("Starting deletion of {$count} projects...");

        foreach ($projects as $project) {
            try {
                $clockify->deleteProject($project['id']);
                $this->line("<fg=yellow>🗑 Deleted:</> {$project['name']}");
            } catch (Exception $e) {
                $this->error("🚨 Failed to delete {$project['name']}: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("Cleanup completed.");
    }
}
