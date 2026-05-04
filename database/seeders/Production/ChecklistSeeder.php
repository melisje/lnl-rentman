<?php

namespace Database\Seeders\Production;

use App\Http\Controllers\Production\ChecklistTemplateController;
use App\Models\Production\Checklist;
use App\Models\Production\ChecklistTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ChecklistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Checklist::whereNotNull('id')->delete();


        $template = ChecklistTemplate::first();

        // for all projects create a checklist with 10 items
        $projects = \App\Models\Rentman\Project::orderBy('planperiod_start')
            ->where('planperiod_start','>=', now()->subDays(30))
            ->where('planperiod_start','<=', now()->addDays(180))
            ->get();

        foreach ($projects as $project)
        {
            $checklist = Checklist::create(
                [
                    'project_id' => $project->id ,
                    'name' => 'Checklist for project ' . $project->number . " (" . $project->displayname . ")",
                    // 'name' => 'Checklist for project ' . $project->id,
                ]
            );

            $templateItems = $template->items()->get();

            foreach ($templateItems as $itemData) {
                $checklist->items()->create([
                    'name' => $itemData['name'],
                    'sequence' => $itemData['sequence'],
                    'is_completed' => fake()->boolean(75), // 75% kans dat het item voltooid is
                ]);
            }
        }
    }
}
