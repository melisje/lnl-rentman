<?php

namespace App\Models\Rentman;

use App\Models\Production\Checklist;
use App\Models\Production\ChecklistItem;
use App\Models\Production\ChecklistTemplate;
use App\Scopes\AccountScope;
use Dom\Attr;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class Project extends Model
{
    protected $table = 'rm_projects';
    protected $primaryKey = 'id';
    // public $incrementing = true;

    /**
     * The attributes that aren't mass assignable.
     * An empty list means that all fields are mass assignable
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];

    /**
     * De casts die moeten worden toegepast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime', // Optioneel: direct formatteren
            'modified_at' => 'datetime', // Optioneel: direct formatteren
            'created' => 'datetime', // Dit cast de kolom naar Carbon
            'modified' => 'datetime', // Dit cast de kolom naar Carbon
            'usageperiod_start' => 'datetime', // Dit cast de kolom naar Carbon
            'usageperiod_end' => 'datetime', // Dit cast de kolom naar Carbon
            'planperiod_start' => 'datetime', // Dit cast de kolom naar Carbon
            'planperiod_end' => 'datetime', // Dit cast de kolom naar Carbon
            'equipment_period_from' => 'datetime', // Dit cast de kolom naar Carbon
            'equipment_period_to' => 'datetime', // Dit cast de kolom naar Carbon
            'custom' => 'array', // Dit cast de JSON-kolom naar een PHP-array
        ];
    }

    /**
     * Boot the model and apply the global scope.
     */
    protected static function booted()
    {
        // Filter all queries automatically based on the current account.
        // If you do want to run a query without this scope, you can
        // use: Project::withoutGlobalScope(AccountScope::class)->get();
        static::addGlobalScope(new AccountScope);
    }

    /**
     * Get the subprojects for the project.
     */
    public function subprojects(): HasMany
    {
        return $this->hasMany(SubProject::class,'projects_id','id');
    }

    /**
     * Haal alle geplande crew op voor het gehele project (over alle subprojecten heen)
     */
    public function projectFunctions(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProjectFunction::class,   // Het doel
            SubProject::class,    // De eerste tussenstap
            'projects_id',        // Foreign key op SubProject tabel (naar Project)
            'subproject_id',      // Foreign key op ProjectCrew tabel (naar SubProject)
            'id',                 // Local key op Project tabel
            'id'                  // Local key op SubProject tabel
        );
    }

    /**
     * Get the project type object for the project.
     */
    public function projectType(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class, 'project_type_id', 'id');
    }

    /**
     * Haalt unieke crewleden op voor het gehele project (over alle subprojecten heen)
     */
    public function projectCrewUnique()
    {
        return $this->subprojects->flatMap->projectFunctions->flatMap->projectCrew->map->member->unique('id');
    }

    /**
     * Calculate the number of subprojects for this project
     */
    public function nrOfSubprojects(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->subprojects()->count(),
        );
    }

    /**
     * Berekende status op basis van subprojecten
     */
    protected function calculatedStatus(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Haal alle unieke statussen van de subprojecten op
                $uniqueStatuses = $this->subprojects->pluck('status')->filter()->unique();


                // replace status paths with human readable names using the Status model
                $uniqueStatuses = $uniqueStatuses->map(function ($statusPath) {
                    $statusId = extract_id($statusPath);
                    $status = Status::where('account', $this->account)
                        ->where('rm_id', $statusId)
                        ->first();
                    return $status ? $status->name : "????";
                });

                // if no statuses are found, return "geen subprojecten"
                if ($uniqueStatuses->isEmpty()) {
                    return 'geen subprojecten';
                }

                // If only one unique status is found, return that status as the
                // calculated status for the project
                if ($uniqueStatuses->count() === 1) {
                    return $uniqueStatuses->first();
                }

                // Otherwise, if there are multiple unique statuses, we can
                // return a combined string
                return $uniqueStatuses->implode(',');
            },
        )->shouldCache();
    }

    /**
     * Haalt de menselijke naam van de status op.
     */
    protected function statusName(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Extraheer de rm_id uit het pad (bijv. /status/12 -> 12)
                // We gebruiken hier de logica van de helper:
                // $statusId = Str::afterLast(trim($this->status, '/'), '/');
                $statusId = extract_id($this->status);

                if (!$statusId) {
                    return 'Geen status';
                }

                // 2. Zoek in de Status tabel naar de match voor dit account en rm_id
                // Cache dit resultaat eventueel voor performance bij loops
                $status = Status::where('account', $this->account)
                    ->where('rm_id', $statusId)
                    ->first();

                return $status ? $status->name : "????";
            },
        );
    }

    /**
     * Haalt de menselijke naam van de Project Manager op.
     */
    protected function pmName(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Extraheer de rm_id uit het pad (bijv. /crew/12 -> 12)
                // We gebruiken hier de logica van de helper:
                // $statusId = Str::afterLast(trim($this->status, '/'), '/');
                $crewid = extract_id($this->project_manager);

                if (!$crewid) {
                    return null;
                }

                // 2. Zoek in de Status tabel naar de match voor dit account en rm_id
                // Cache dit resultaat eventueel voor performance bij loops
                $crew = Crew::where('account', $this->account)
                    ->where('rm_id', $crewid)
                    ->first();

                return $crew ? $crew->displayname : null;
            },
        );
    }

    /**
     * Haalt de menselijke naam van de Account Manager op.
     */
    protected function amName(): Attribute
    {
        return Attribute::make(
            get: function () {
                // 1. Extraheer de rm_id uit het pad (bijv. /crew/12 -> 12)
                // We gebruiken hier de logica van de helper:
                // $statusId = Str::afterLast(trim($this->status, '/'), '/');
                $crewid = extract_id($this->account_manager);

                if (!$crewid) {
                    return null;
                }

                // 2. Zoek in de Status tabel naar de match voor dit account en rm_id
                // Cache dit resultaat eventueel voor performance bij loops
                $crew = Crew::where('account', $this->account)
                    ->where('rm_id', $crewid)
                    ->first();

                return $crew ? $crew->displayname : null;
            },
        );
    }


    /**
     * Een accessor om de volledige naam van het project te krijgen, bestaande
     * uit het nummer en de naam. Bijvoorbeeld: "1234 - Mijn Project".
     */
    public function getFullDisplayNameAttribute()
    {
        return "{$this->number} - {$this->name}";
    }


    /**
     * Fetch the budgets for this project by looking at the project functions
     * with the "budget" tag. The project_function duration fields contains
     * a value in seconds. We convert this to hours by dividing by 3600.
     * This method returns an array with the summed duration for each
     * budget type (e.g. "light", "sound", "rigging") that is found
     * in the tags of the project functions.
     *
     * @return Collection A collection where the keys are the budget types
     * (e.g. "rigging", "lighting") and the values are the duration in
     * hours for that budget type.
     */
    public function getBudgetsAttribute():Collection
    {

        $budgets = $this->projectFunctions
            // 1. Filter alleen de items waar 'budget' in de tags voorkomt
            ->filter(fn($item) => str_contains($item->tags, 'budget'))

            // 2. Loop door de gefilterde lijst en bouw de som op
            ->reduce(function ($carry, $item) {
                // Splits de tags (bijv. "budget, light, sound" wordt ['budget', 'light', 'sound'])
                $tags = array_map('trim', explode(',', $item->tags));

                foreach ($tags as $tag) {
                    // We negeren de algemene 'budget' tag zelf voor de som
                    if ($tag !== 'budget' && !empty($tag)) {
                        $carry[$tag] = ($carry[$tag] ?? 0) + $item->duration / 3600;
                    }
                }

                return $carry;
            }, []); // Start met een lege array

            return collect($budgets);
    }

    /**
     * Fetch the budgets for this project by looking at the project functions
     * with the "budget" tag. The project_function price_total fields contains
     * a value in euro.
     * This method returns an array with the summed budget prices for each
     * budget type (e.g. "light", "sound", "rigging") that is found
     * in the tags of the project functions.
     *
     * @return Collection A collection where the keys are the budget types
     * (e.g. "rigging", "lighting") and the values are the duration in
     * hours for that budget type.
     */
    public function getEuroBudgetsAttribute():Collection
    {

        $budgets = $this->projectFunctions
            // 1. Filter alleen de items waar 'budget' in de tags voorkomt
            ->filter(fn($item) => str_contains($item->tags, 'budget'))

            // 2. Loop door de gefilterde lijst en bouw de som op
            ->reduce(function ($carry, $item) {
                // Splits de tags (bijv. "budget, light, sound" wordt ['budget', 'light', 'sound'])
                $tags = array_map('trim', explode(',', $item->tags));

                foreach ($tags as $tag) {
                    // We negeren de algemene 'budget' tag zelf voor de som
                    if ($tag !== 'budget' && !empty($tag)) {
                        $carry[$tag] = ($carry[$tag] ?? 0) + $item->price_total;
                    }
                }

                return $carry;
            }, []); // Start met een lege array

            return collect($budgets);
    }

    /**
     * Calculate the total budget consumption for the budget types budgetted for
     * this project.
     *
     * @TODO: In deze voorbeeldimplementatie gebruiken we random waarden om de
     *        consumptie te simuleren, maar in een echte implementatie zou je
     *        hier de logica moeten toepassen om de consumptie te berekenen
     *        op basis van gerelateerde data (bijv. timesheets, equipment
     *        usage, etc.)
     */
    public function getBudgetConsumptionAttribute(): Collection
    {
        $budgets = $this->budgets;

        // Voor elk budgettype, bereken de consumptie. In dit voorbeeld gaan we
        // ervan uit dat de consumptie een random percentagie is van het budget,
        // maar in een realistisch scenario zou je hier een andere logica
        // kunnen toepassen.
        $consumptions = [];
        foreach ($budgets as $type => $amount)
        {
            // A random number between 0 and the budget amount, to simulate
            $percentage = rand(0, 100) / 100; // Random percentage tussen 0% en 100%
            // consumption. In a real implementation, you would replace
            // this with the actual logic to calculate consumption
            // based on related data (e.g. timesheets, equipment
            // usage, etc.)
            $consumptions[$type] = $amount * $percentage; // Hier zou je de echte consumptie moeten berekenen
        }
        // Tel alle budgetten bij elkaar op voor een totaal consumptie
        return collect($consumptions);
    }

    /**
     * Calculate the budget consumption in percentage
     */
    public function getBudgetConsumptionPercentageAttribute(): Collection
    {
        $budgets = $this->budgets;
        $consumptions = $this->budgetConsumption;

        $percentages = [];
        foreach ($budgets as $type => $amount)
        {
            $consumption = $consumptions[$type] ?? 0;
            // Bereken het percentage consumptie ten opzichte van het budget
            $percentages[$type] = ($amount > 0) ? ($consumption / $amount) * 100 : 0;
        }
        return collect($percentages);
    }

    /**
     * Calculate the number of days until the project starts, based on the
     * usageperiod_start date. If the start date is in the past, this will
     * a negative value. We can use this in the view to show how many
     * days until the project starts, or if it's already started,
     * how many days ago it started.
     */
    public function getDaysUntilStartUsageAttribute()
    {
        if (!$this->usageperiod_start) {
            return null;
        }

        $start = $this->usageperiod_start;

        // Bereken het verschil in dagen.
        // return (int) (now()->diffInHours($start))/24;
        return (int) (now()->diffInDays($start));
    }

    /**
     * Calculate the number of days until the project starts, based on the
     * usageperiod_start date. If the start date is in the past, this will
     * a negative value. We can use this in the view to show how many
     * days until the project starts, or if it's already started,
     * how many days ago it started.
     */
    public function getDaysUntilStartPlanAttribute()
    {
        if (!$this->planperiod_start) {
            return null;
        }

        $start = $this->planperiod_start;

        // Bereken het verschil in dagen.
        // return (int) (now()->diffInHours($start))/24;
        return (int) (now()->diffInDays($start));
    }

    /**
     * Calculate the number of weeks until the project starts, based on the
     * usageperiod_start date. If the start date is in the past, this will
     * return a negative value. We can use this in the view to show how
     * many weeks until the project starts, or if it's already started,
     * how many weeks ago it started.
     */
    public function getWeeksUntilStartUsageAttribute()
    {
        if (!$this->usageperiod_start) {
            return null;
        }

        $start = $this->usageperiod_start;

        // Bereken het verschil in weken. We gebruiken diffInWeeks met de
        // absolute waarde uitgeschakeld, zodat we negatieve waarden
        // krijgen voor projecten die in het verleden zijn begonnen.

        // return (int) (now()->diffInWeeks($start, false, true));
        $diff =  (int) (now()->diffInHours($start)) / 24 / 7;
        return ($diff >= 0) ? ceil($diff) : floor($diff);
    }

    /**
     * Calculate the number of weeks until the project starts, based on the
     * usageperiod_start date. If the start date is in the past, this will
     * return a negative value. We can use this in the view to show how
     * many weeks until the project starts, or if it's already started,
     * how many weeks ago it started.
     */
    public function getWeeksUntilStartPlanAttribute()
    {
        if (!$this->planperiod_start)
        {
            return null;
        }

        $start = $this->planperiod_start;
        // Log::debug("Calculating weeks until start for project {$this->id}, planperiod_start: {$start}");

        // Bereken het verschil in weken. We gebruiken diffInWeeks met de
        // absolute waarde uitgeschakeld, zodat we negatieve waarden
        // krijgen voor projecten die in het verleden zijn begonnen.

        $diff =  (int) (now()->diffInHours($start)) / 24 / 7;
        return floor($diff);
    }

    /**
     * Add a checklist to the project based on a given template. This method
     * creates a new Checklist model, associates it with this project, and then
     * calls the addTemplateItems method to populate the checklist with items
     * based on the provided template.
     */
    public function addChecklist(ChecklistTemplate $template): Project
    {

        $checklist = Checklist::create(
            [
                'project_id' => $this->id,
                'name' => 'Checklist for project ' . $this->full_display_name . " (" . $this->number . ")",
                'remarks' => 'Checklist created on ' . now()->toDateTimeString() . " and based on template " . $template->name,
            ]
        );

        // Add items to the checklist based on the template
        $checklist->addTemplateItems($template);


        // return this model to allow chaining
        return $this;
    }

    /**
     * Get the checklists for the project.
     */
    public function checklists(): HasMany
    {
        return $this->hasMany(Checklist::class, 'project_id', 'id');
    }

    /**
     * Get the checklist items for the project.
     */
    public function checklistItems(): HasManyThrough
    {
        return $this->hasManyThrough(
            ChecklistItem::class,   // Het doel
            Checklist::class,    // De eerste tussenstap
            'project_id',        // Foreign key op Checklist tabel (naar Project)
            'checklist_id',      // Foreign key op ChecklistItem tabel (naar Checklist)
            'id',                 // Local key op Project tabel
            'id'                  // Local key op Checklist tabel
        );
    }

    /**
     * Calculate the number of checklist items for this project, by counting
     * the related checklist items through the checklists relationship.
     * We can use Eloquent's withCount('checklistItems') in the
     * controller to eager load this count and avoid N+1
     * query issues.
     */
    public function getCountChecklistItemsAttribute(): int
    {
        return $this->checklistItems()->count();
    }

    /**
     * Calculate the number of completed checklist items for this project, by counting
     * the related checklist items through the checklists relationship.
     * We can use Eloquent's withCount('checklistItems') in the
     * controller to eager load this count and avoid N+1
     * query issues.
     */
    public function getCountChecklistItemsCompletedAttribute(): int
    {
        return $this->checklistItems()->where('is_completed', true)->count();
    }

}