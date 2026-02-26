<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $table = 'rm_projects';
    protected $primaryKey = 'id';
    // public $incrementing = true;

    protected $appends = ['calculated_status', 'status_name', 'pm_name', 'am_name', 'nr_of_subprojects'];

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
     * Get the subprojects for the project.
     */
    public function subprojects(): HasMany
    {
        return $this->hasMany(SubProject::class,'projects_id','id');
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
                $uniqueStatuses = $this->subprojects->pluck('status')->unique();

                // 2. Als er geen subprojecten zijn
                if ($uniqueStatuses->isEmpty()) {
                    return 'geen subprojecten';
                }

                // 3. Als er precies 1 unieke status is, hebben ze allemaal dezelfde status
                if ($uniqueStatuses->count() === 1) {
                    return $uniqueStatuses->first();
                }

                // 4. In alle andere gevallen zijn de statussen verschillend
                return 'gevarieerd';
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
}