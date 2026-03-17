<?php

namespace App\Console\Commands\Rentman;

use App\Models\Rentman\Account;
use App\Models\Rentman\Crew;
use App\Models\Rentman\Project;
use App\Models\Rentman\ProjectCrew;
use App\Models\Rentman\ProjectFunction;
use App\Models\Rentman\SubProject;
use App\Services\Rentman\Api\RentmanApiService;
use Illuminate\Console\Command;

class syncDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rentman:sync-delete {--dry-run : Voer de schoonmaak uit zonder echt iets te verwijderen}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete non existing items in Projects, SubProjects, ProjectFunctions, ProjectCrew and Crew';

    /**
     * Execute the console command.
     */
    public function handle(RentmanApiService $rentmanService)
    {
        // $account = Account::first();
        $account = Account::find('llstageservice');
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn("!!! DRY RUN MODUS ACTIEF: Er wordt niets verwijderd !!!");
        }

        $entities = [
            // 'projectcrew'      => ProjectCrew::class,
            // 'projectfunctions' => ProjectFunction::class,
            // 'subprojects'      => SubProject::class,
            'projects'         => Project::class,
            // 'crew'             => Crew::class,
        ];

        foreach ($entities as $endpoint => $modelClass) {
            $this->info("===== Controleren van $endpoint...");

            $activeIds = $rentmanService->getActiveRmIds($account, $endpoint, [$this,'log']);
            $cnt = count($activeIds);
            $this->info(". +-> $endpoint contains $cnt active ids");

            if (empty($activeIds)) {
                continue;
            }

            // Zoek de records die verwijderd zouden worden
            $query = $modelClass::where('account', $account->account)
                ->whereNotIn('rm_id', $activeIds);

            $toDeleteCount = $query->count();

            if ($toDeleteCount > 0) {
                if ($isDryRun) {
                    // Toon alleen wat er zou gebeuren
                    $this->line("\n  [Dry Run] Zou $toDeleteCount records verwijderen van $endpoint.");

                    // Optioneel: toon de eerste paar namen/IDs
                    $samples = $query->limit(3)->get()->pluck('displayname')->implode(', ');
                    $this->line("  [Dry Run] Voorbeelden: $samples...");
                } else {
                    // De echte actie
                    // $deleted = $query->delete();
                    $deleted = 0;
                    $this->comment("  -> $deleted records succesvol verwijderd.");
                }
            } else {
                $this->info("  Geen records om te verwijderen voor $endpoint.");
            }
        }
    }

    function log($msg, ?string $type='info'):void
    {
        switch ($type)
        {
            case 'error':
                $this->error($msg);
                break;
            case 'comment':
                $this->comment($msg);
                break;
            case 'warn':
                $this->warn($msg);
                break;
            case 'line':
                $this->line($msg);
                break;
            case 'output':
                $this->output->write($msg);
                break;
            default:
                $this->info($msg);
        }
    }
}
