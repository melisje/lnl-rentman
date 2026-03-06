<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentmanFunction extends Model
{
    use HasFactory;

    protected $table = 'rm_functions';

    protected $fillable = [
        'account',
        'rm_id',
        'created',
        'modified',
        'creator',
        'displayname',
        'cost_rate',
        'cost_accommodation',
        'cost_catering',
        'cost_travel',
        'cost_other',
        'price_rate',
        'price_accommodation',
        'price_catering',
        'price_travel',
        'price_other',
        'project',
        'subproject',
        'is_template',
        'group',
        'name_external',
        'name',
        'travel_time_before',
        'travel_time_after',
        'usageperiod_start',
        'planperiod_start_schedule_is_start',
        'usageperiod_start_schedule_is_start',
        'planperiod_end_schedule_is_start',
        'usageperiod_end_schedule_is_start',
        'usageperiod_end',
        'planperiod_start',
        'planperiod_end',
        'type',
        'duration',
        'amount',
        'break',
        'distance',
        'twoway',
        'taxclass',
        'ledger',
        'order',
        'remark_client',
        'remark_planner',
        'remark_crew',
        'in_financial',
        'in_planning',
        'is_plannable',
        'recurrence_group',
        'recurrence_enddate',
        'recurrence_interval_unit',
        'recurrence_interval',
        'recurrence_weekdays',
        'price_fixed',
        'price_variable',
        'costs_fixed',
        'costs_variable',
        'price_total',
        'costs_total',
        'tags',
        'custom'
    ];

    protected $casts = [
        'created' => 'datetime',
        'modified' => 'datetime',
        'usageperiod_start' => 'datetime',
        'usageperiod_end' => 'datetime',
        'planperiod_start' => 'datetime',
        'planperiod_end' => 'datetime',
        'is_template' => 'boolean',
        'twoway' => 'boolean',
        'in_financial' => 'boolean',
        'in_planning' => 'boolean',
        'is_plannable' => 'boolean',
        'custom' => 'array',
        'price_total' => 'decimal:2',
        'costs_total' => 'decimal:2',
    ];
}