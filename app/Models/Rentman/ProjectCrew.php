<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProjectCrew extends Model
{
    use HasFactory;

    /**
     * De tabelnaam die expliciet wordt gedefinieerd.
     */
    protected $table = 'rm_projectcrew';

    /**
     * Omdat de brondata eigen 'created' en 'modified' velden heeft,
     * zetten we de standaard Laravel timestamps uit of mappen we ze.
     */
    public $timestamps = false;

    /**
     * Velden die mass-assignable zijn.
     */
    protected $fillable = [
        'rm_id', // De oude 'id' uit de JSON
        'account',
        'created',
        'modified',
        'creator',
        'displayname',
        'cost_rate',
        'cost_accommodation',
        'cost_catering',
        'cost_travel',
        'cost_other',
        'function',
        'function_id',
        'crewmember',
        'crew_id',
        'subproject_id',
        'visible',
        'planperiod_start',
        'planperiod_end',
        'transport',
        'remark',
        'remark_planner',
        'invoice_reference',
        'project_leader',
        'is_visible_on_dashboard',
        'costs',
        'cost_actual',
        'hours_registered',
        'hours_planned',
        'cost_planned',
        'diff_cost',
        'diff_hours',
        'activity_status',
        'updateHash',
        'custom'
    ];

    /**
     * Type casting voor specifieke velden.
     */
    protected $casts = [
        'created' => 'datetime',
        'modified' => 'datetime',
        'planperiod_start' => 'datetime',
        'planperiod_end' => 'datetime',
        'visible' => 'boolean',
        'project_leader' => 'boolean',
        'is_visible_on_dashboard' => 'boolean',
        'custom' => 'array', // Handelt de {} in JSON af als PHP array
        'cost_actual' => 'decimal:2',
        'cost_planned' => 'decimal:2',
        'diff_cost' => 'decimal:2',
    ];

    /**
     * Helper methode om uren (in seconden) om te zetten naar leesbare uren.
     */
    public function getHoursRegisteredInDecimalAttribute()
    {
        return $this->hours_registered / 3600;
    }
}