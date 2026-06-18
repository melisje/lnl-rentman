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
        'update_hash',
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
            if ($activity->isDirty('time_registration') && !empty($activity->time_registration)) {
                $rmId = basename($activity->time_registration);

                if (is_numeric($rmId)) {
                    // Zoek in de rm_timeregistrations tabel naar het record met dit rm_id
                    // Note: We pakken hier alleen de 'id' (primary key) van dat record
                    $related = TimeRegistration::where('rm_id', (int) $rmId)->first();
                    $activity->time_registration_id = $related ? $related->id : null;
                } else {
                    $activity->time_registration_id = null;
                }
            } elseif (empty($activity->time_registration)) {
                $activity->time_registration_id = null;
            }

            // 2. Zoek de interne database ID op basis van de rm_id uit 'project_function'
            if ($activity->isDirty('project_function') && !empty($activity->project_function)) {
                $rmId = basename($activity->project_function);

                if (is_numeric($rmId)) {
                    // Zoek in de rm_project_functions tabel naar het record met dit rm_id
                    $related = ProjectFunction::where('rm_id', (int) $rmId)->first();
                    $activity->project_function_id = $related ? $related->id : null;
                } else {
                    $activity->project_function_id = null;
                }
            } elseif (empty($activity->project_function)) {
                $activity->project_function_id = null;
            }

        });
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