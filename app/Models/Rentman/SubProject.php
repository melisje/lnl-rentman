<?php

namespace App\Models\Rentman;

use App\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class SubProject extends Model
{
    /** @use HasFactory<\Database\Factories\Rentman\SubProjectFactory> */
    use HasFactory;

    protected $table = 'rm_subprojects';
    // protected $primaryKey = 'id';
    // public $incrementing = false;
    protected $guarded = ['id'];
    protected $appends = ['status_name'];

    /**
     * De casts die moeten worden toegepast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created' => 'datetime', // Dit cast de kolom naar Carbon
            'modified' => 'datetime', // Dit cast de kolom naar Carbon
            'usageperiod_start' => 'datetime', // Dit cast de kolom naar Carbon
            'usageperiod_end' => 'datetime', // Dit cast de kolom naar Carbon
            'planperiod_start' => 'datetime', // Dit cast de kolom naar Carbon
            'planperiod_end' => 'datetime', // Dit cast de kolom naar Carbon
            'equipment_period_from' => 'datetime', // Dit cast de kolom naar Carbon
            'equipment_period_to' => 'datetime', // Dit cast de kolom naar Carbon
            'created_at' => 'datetime:Y-m-d H:i', // Optioneel: direct formatteren
            'modified_at' => 'datetime:Y-m-d H:i', // Optioneel: direct formatteren
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
     * Scope of projects that are released for the warehouse.
     * Select only subprojects where the 'vrijgave_voor_warehouse' column is true.
     */
    public function scopeReleasedForWarehouse(Builder $query): Builder
    {
        return $query->where('vrijgave_voor_warehouse', true);
    }

    /**
     * Scope of projects that are NOT cancelled (status name is not "Geannuleerd").
     */
    public function scopeNotCancelled(Builder $query): Builder
    {
        // find the status_id for "Geannuleerd" for the current account
        // and filter the subprojects that do NOT have this status_id in their 'status' column.
        $statusId = Status::where('name', 'Geannuleerd')
            ->value('rm_id');

        return $query->where('status', '!=', "/statuses/$statusId");
    }

    /**
     * Get the Project that owns the subproject.
     */
    public function parentProject(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'projects_id', 'id');
    }

    /**
     * Get the ProjectFunctions for the subproject.
     */
    public function projectFunctions(): HasMany
    {
        return $this->hasMany(ProjectFunction::class,'subproject_id','id');
    }

    /**
     * Get all the ProjectCrew planned for the subproject via the ProjectFunction
     */
    public function projectCrew(): HasManyThrough
    {
        return $this->hasManyThrough(ProjectCrew::class,
        ProjectFunction::class,
        'subproject_id',
        'function_id',
        'id',
        'id');
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
}

