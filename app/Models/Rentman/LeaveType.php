<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveType extends Model
{
    use HasFactory;

    /**
     * De tabel die gekoppeld is aan het model.
     *
     * @var string
     */
    protected $table = 'rm_leavetypes';

    /**
     * De attributen die massaal toegewezen mogen worden.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rm_id',
        'account',
        'created',
        'modified',
        'creator',
        'balance_start_date',
        'type',
        'displayname',
        'name',
        'payroll_code',
        'color',
        'requires_approval',
        'affects_availability',
        'has_balance',
        'is_labor',
        'has_calculated_duration',
        'can_have_activities',
        'counts_in_totals',
        'custom',

    ];

    /**
     * De attributen die gecast moeten worden naar een specifiek datatype.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rm_id'                   => 'integer',
        'requires_approval'       => 'boolean',
        'affects_availability'    => 'boolean',
        'has_balance'             => 'boolean',
        'has_calculated_duration' => 'boolean',
        'can_have_activities'     => 'boolean',
        'counts_in_totals'        => 'boolean',
        'is_labor'                => 'integer',
        'custom'                  => 'array', // Zorgt ervoor dat je direct met de JSON kunt werken als PHP array
    ];
}
