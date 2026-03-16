<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
     * Get the parent Project for this ProjectFunction.
     */
    public function parentProject(): BelongsTo
    {
        return $this->belongsTo(Project::class,'project_id','id');
    }

    /**
     * Get the parent SubProject for this ProjectFunction.
     */
    public function parentSubproject(): BelongsTo
    {
        return $this->belongsTo(SubProject::class,'subproject_id','id');
    }

    /**
     * Get the ProjectFunctions for this ProjectFunction.
     */
    public function projectCrew(): HasMany
    {
        return $this->hasMany(ProjectCrew::class, 'function_id', 'id');
    }

    /**
     * Helper om enkel de numerieke ID uit een Rentman pad te halen
     * Voorbeeld: /subprojects/24844 -> 24844
     */
    public static function extractId($path)
    {
        return preg_replace('/[^0-9]/', '', $path);
    }
}