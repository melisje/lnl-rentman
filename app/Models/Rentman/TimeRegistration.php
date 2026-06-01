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
}
