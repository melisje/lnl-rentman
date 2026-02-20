<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class CustomFieldMapping extends Model
{
    //
    protected $table = 'rm_customfield_mappings';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $guarded = [];
    // protected $fillable = ['rm_id', 'account', 'displayname'];
    protected $appends = ['display_name', 'custom_name'];

    /**
     * Get the custom fields for the mapping.
     */
    public function customField(): BelongsTo
    {
        return $this->belongsTo(CustomField::class, 'customfield_id', 'id');
    }

    public function displayName() : Attribute
    {
        return Attribute::make(
            get: function () {
                return $this->customField ? $this->customField->name : $this->customfield_id;
            },
        );
    }

    public function customName() : Attribute
    {
        return Attribute::make(
            get: function () {
                return "custom_" . $this->rm_id;
            },
        );
    }
}
