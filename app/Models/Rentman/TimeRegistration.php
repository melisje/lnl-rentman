<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeRegistration extends Model
{
    use HasFactory;

    /**
     * De tabel die gekoppeld is aan het model.
     * Omdat de tabelnaam afwijkt van de standaard meervoudsvorm (time_registrations),
     * moeten we deze hier expliciet definiëren.
     *
     * @var string
     */
    protected $table = 'rm_timeregistrations';

    /**
     * De attributen die massaal toegewezen mogen worden (Mass Assignable).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rm_id',
        'account',
        'created',
        'modified',
        'start',
        'end',
        'creator',
        'crewmember',
        'leavetype',
        'leaverequest',
        'displayname',
        'status',
        'remark',
        'distance',
        'is_lunch_included',
        'duration',
        'break_duration',
        'break_duration_with_start_end',
        'travel_time',
        'correction_duration',
        'custom',
        'type_activiteit',  // L&L
        'approval',        // L&L
        'factuur_ontvangen', // L&L
    ];

    /**
     * De attributen die gecast moeten worden naar een specifiek datatype.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rm_id' => 'integer',
        'start' => 'datetime',
        'end' => 'datetime',
        'is_lunch_included' => 'boolean',
        'distance' => 'integer',
        'duration' => 'integer',
        'break_duration' => 'integer',
        'break_duration_with_start_end' => 'integer',
        'travel_time' => 'integer',
        'correction_duration' => 'integer',
    ];

    /**
     * Automatische conversie logica op basis van rm_id uit de paden.
     */
    protected static function booted(): void
    {
        static::saving(function (TimeRegistration $registration)
        {

            // 1. Zoek de interne database ID op basis van de rm_id uit 'time_registration'
            if ($registration->isDirty('crewmember'))
            {
                $registration->updateCrewmemberId();
            }

            // 2. Zoek de interne database ID op basis van de rm_id uit 'project_function'
            if ($registration->isDirty('leavetype'))
            {
                $registration->updateLeavetypeId();
            }
        });
    }

    /**
     * Update the crewmember_id based on the rm_id
     * that is found in the crew field.
     */
    public function updateCrewmemberId():void
    {
        if (!empty($this->crewmember))
        {
            $rmId = basename($this->crewmember);

            if (is_numeric($rmId))
            {
                $related = Crew::where('rm_id', (int) $rmId)->first();
                $this->crewmember_id = $related ? $related->id : null;
            } else
            {
                $this->crewmember_id = null;
            }
        } else
        {
            $this->crewmember_id = null;
        }
    }

    /**
     * Update the leavetype_id based on the rm_id
     * that is found in the leavetype field.
     */
    public function updateLeavetypeId():void
    {
        if (!empty($this->leavetype))
        {
            $rmId = basename($this->leavetype);

            if (is_numeric($rmId))
            {
                $related = LeaveType::where('rm_id', (int) $rmId)->first();
                $this->leavetype_id = $related ? $related->id : null;
            } else
            {
                $this->leavetype_id = null;
            }
        } else
        {
            $this->leavetype_id = null;
        }
    }
}
