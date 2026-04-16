<?php

namespace App\Models\Rentman;

use App\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Application extends Model
{
    //

    protected $table = 'rm_applications';
    protected $primaryKey = 'id';
    // public $incrementing = true;

    /**
     * The attributes that aren't mass assignable.
     * An empty list means that all fields are mass assignable
     *
     * @var array<string>|bool
     */
    protected $guarded = ['id'];


    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Boot the model and apply the global scope.
     */
    protected static function booted()
    {
        // Filter all queries automatically based on the current account.
        // If you do want to run a query without this scope, you can
        // use: Project::withoutGlobalScope(AccountScope::class)->get();
        static::addGlobalScope(new AccountScope);
    }

    /**
     * Get the subprojects for the project.
     */
    public function projectTypes(): HasManyThrough
    {
        return $this->hasManyThrough(
            ProjectType::class,   // Het doel
            SubProject::class,    // De eerste tussenstap
            'projects_id',        // Foreign key op SubProject tabel (naar Project)
            'subproject_id',      // Foreign key op ProjectCrew tabel (naar SubProject)
            'id',                 // Local key op Project tabel
            'id'                  // Local key op SubProject tabel
        );
    }
}
