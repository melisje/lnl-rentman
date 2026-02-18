<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomFieldMapping extends Model
{
    //
    protected $table = 'rm_customfield_mappings';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $guarded = [];
    // protected $fillable = ['rm_id', 'account', 'displayname'];

    /**
     * Get the custom fields for the mapping.
     */
    public function mapping(): BelongsTo
    {
        return $this->belongsTo(Mapping::class, 'mapping_id', 'id');
    }
}
