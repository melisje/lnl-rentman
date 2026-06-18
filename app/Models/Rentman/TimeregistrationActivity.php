<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeregistrationActivity extends Model
{
    use HasFactory;

    // Expliciet de tabelnaam inclusief prefix definiëren
    protected $table = 'rm_timeregistration_activities';

    /**
     * De attributen die massaal toewijsbaar zijn.
     */
    protected $fillable = [
        'rm_id',
        'account',

        // Relatie string-velden (ruwe API data)
        'time_registration',
        'project_function',
        'subproject_function',

        // Foreign Key IDs
        'time_registration_id',
        'project_function_id',

        'created',
        'modified',
        'creator',
        'displayname',
        'description',
        'duration',
        'is_activity',
        'from',
        'to',
        'updateHash',
    ];

    /**
     * De attributen die gecast moeten worden naar specifieke types.
     */
    protected $casts = [
        'is_activity' => 'boolean',
        'from' => 'datetime',
        'to' => 'datetime',
        'duration' => 'integer',
        'rm_id' => 'integer',
    ];

    /**
     * Automatische conversie logica op basis van rm_id uit de paden.
     */
    protected static function booted(): void
    {
        static::saving(function (TimeregistrationActivity $activity) {

            // 1. Zoek de interne database ID op basis van de rm_id uit 'time_registration'
            if ($activity->isDirty('time_registration'))
            {
                $activity->updateTimeregistrationId();
            }

            // 2. Zoek de interne database ID op basis van de rm_id uit 'project_function'
            if ($activity->isDirty('project_function'))
            {
                $activity->updateProjectFunctionId();
            }
        });
    }

    /**
     * Update the timeregistration_id based on the rm_id
     * that is found in the time_registration field.
     */
    public function updateTimeregistrationId():void
    {
        if (!empty($this->time_registration))
        {
            $rmId = basename($this->time_registration);

            if (is_numeric($rmId))
            {
                $related = TimeRegistration::where('rm_id', (int) $rmId)->first();
                $this->time_registration_id = $related ? $related->id : null;
            } else
            {
                $this->time_registration_id = null;
            }
        } else
        {
            $this->time_registration_id = null;
        }
    }

    /**
     * Update the projectfunction_id based on the rm_id
     * that is found in the project_function field.
     */
    public function updateProjectFunctionId(): void
    {
        if (!empty($this->project_function))
        {
            $rmId = basename($this->project_function);

            if (is_numeric($rmId))
            {
                $related = ProjectFunction::where('rm_id', (int) $rmId)->first();
                $this->project_function_id = $related ? $related->id : null;
            } else
            {
                $this->project_function_id = null;
            }
        } else {
            $this->project_function_id = null;
        }
    }

    /**
     * Relatie met TimeRegistration
     * (Ervan uitgaande dat TimeRegistration ook in App\Models\Rentman staat)
     */
    public function timeRegistration(): BelongsTo
    {
        return $this->belongsTo(TimeRegistration::class, 'time_registration_id');
    }

    /**
     * Relatie met ProjectFunction
     * (Ervan uitgaande dat ProjectFunction ook in App\Models\Rentman staat)
     */
    public function projectFunction(): BelongsTo
    {
        return $this->belongsTo(ProjectFunction::class, 'project_function_id');
    }
}