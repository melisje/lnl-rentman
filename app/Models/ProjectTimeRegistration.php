<?php

namespace App\Models;

use App\Models\Rentman\Crew;
use App\Models\Rentman\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectTimeRegistration extends Model
{
    use HasFactory;

    // Overschrijf de standaard "updated_at" kolomnaam
    // const UPDATED_AT = 'modified_at';

    protected $table = 'prod_time_registrations'; // Zorg dat dit overeenkomt met de naam in je migratie

    protected $fillable = [
        'project_id',
        'crewmember_id',
        'budget_type',
        'start',
        'end',
        'duration',
        'remarks',
    ];

    // Zorg dat datums netjes als Carbon-objecten worden behandeld
    protected $casts = [
        'start' => 'datetime',
        'end' => 'datetime',
        'duration' => 'float',
    ];

    /**
     * Relatie naar het Project
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
        // Let op: Pas 'Project::class' aan naar jouw daadwerkelijke modelnaam voor rm_projects
    }

    /**
     * Relatie naar het Crewlid
     */
    public function crewmember(): BelongsTo
    {
        return $this->belongsTo(Crew::class, 'crewmember_id');
        // Let op: Pas 'Crewmember::class' aan naar jouw daadwerkelijke modelnaam voor rm_crew
    }
}
