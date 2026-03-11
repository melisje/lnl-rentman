<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class ProjectFunction extends Model
{
    protected $table = 'rm_project_functions';

    // Alles toestaan voor makkelijke sync, of specificeer je velden in $fillable
    protected $guarded = ['id'];

    protected $casts = [
        'usageperiod_start' => 'datetime',
        'usageperiod_end' => 'datetime',
        'planperiod_start' => 'datetime',
        'planperiod_end' => 'datetime',
        'created' => 'datetime',
        'modified' => 'datetime',
        'is_template' => 'boolean',
        'in_financial' => 'boolean',
        'in_planning' => 'boolean',
        'is_plannable' => 'boolean',
    ];

    /**
     * Helper om enkel de numerieke ID uit een Rentman pad te halen
     * Voorbeeld: /subprojects/24844 -> 24844
     */
    public static function extractId($path)
    {
        return preg_replace('/[^0-9]/', '', $path);
    }
}