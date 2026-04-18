<?php

namespace App\Models\Rentman;

use App\Scopes\AccountScope;
use Illuminate\Database\Eloquent\Builder;
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



    public function projectTypes($account)
    {
        return ProjectTypeApplicationMapping::where('account', $account)
            ->where('application_id', $this->id)
            ->get();
    }

    /**
     * Find all projects that are related to this application based on the
     * table rm_project_type_application_mapping.
     * We need to ignore the global scope here, because that scope would
     * filter on the current account, while we want to be able to
     * specify the account as a parameter to this function.
     */
    public function projects($account): Builder
    {
        $projects = Project::withoutGlobalScope(AccountScope::class)
            ->where('account', $account)
            ->whereIn('project_type_id', function ($query) use ($account) {
                $query->select('project_type_id')
                    ->from('rm_project_type_application_mappings')
                    ->where('application_id', $this->id)
                    ->where('account', $account);
            });

        return $projects;

    }
}
