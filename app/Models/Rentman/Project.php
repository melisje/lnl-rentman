<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    protected $table = 'rm_projects';
    protected $primaryKey = 'id';
    // public $incrementing = true;

    protected $appends = ['calculated_status'];

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
}