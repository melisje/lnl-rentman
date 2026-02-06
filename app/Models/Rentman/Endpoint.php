<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Endpoint extends Model
{
    protected $table = 'rm_endpoints';
    protected $primaryKey = 'id';
    public $incrementing = false;
    //

    /**
     * Get the comments for the blog post.
     */
    public function fields(): HasMany
    {
        return $this->hasMany(EndpointField::class);
    }
}
