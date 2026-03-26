<?php

namespace App\Services\Production;

use App\Models\Production\Checklist;
use App\Models\Production\ChecklistItem;
use App\Models\Production\ChecklistTemplate;
use App\Models\Rentman\Project;
use Illuminate\Support\Facades\DB;

class ChecklistService
{
    /**
     * Create a new Checklist instance based on a template for a specific project.
     */
    public function createChecklist(Project $project, ChecklistTemplate $template): Checklist
    {
        // Use a database transaction to ensure data integrity
        return DB::transaction(function () use ($project, $template) {

            // 1. Create the new Checklist
            $checklist = Checklist::create([
                'project_id' => $project->id,
                'name'       => $template->name,
                'remarks'    => $template->remarks,
            ]);

            // 2. Fetch template items ordered by sequence
            $templateItems = $template->items()->orderBy('sequence')->get();

            // 3. Clone each template item to the new checklist
            foreach ($templateItems as $templateItem) {
                $checklist->items()->create([
                    'name'     => $templateItem->name,
                    'sequence' => $templateItem->sequence,
                    'remarks'  => $templateItem->remarks,
                ]);
            }

            return $checklist;
        });
    }
}
