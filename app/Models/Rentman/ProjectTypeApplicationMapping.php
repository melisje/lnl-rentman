<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;

class ProjectTypeApplicationMapping extends Model
{
    protected $table = 'rm_project_type_application_mappings';
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
}


<?php

namespace App\Models\Rentman;

use App\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProjectType extends Model
{
    //

    /**
     * Get the subprojects for the project.
     */
    public function projects(): HasMany
    {
        return $this->hasMany(Project::class, 'projects_id', 'id');
        return $this->hasMany(Project::class, 'project_type_id', 'id');
    }

}
