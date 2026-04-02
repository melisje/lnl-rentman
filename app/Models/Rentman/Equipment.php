<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Equipment extends Model
{
    // Omdat je een afwijkende tabelnaam en namespace gebruikt
    protected $table = 'rm_equipment';

    protected $guarded = []; // Of vul $fillable met alle velden

    protected $casts = [
        'custom' => 'array',
        'in_shop' => 'boolean',
        'surface_article' => 'boolean',
        'shop_featured' => 'boolean',
        'temporary' => 'boolean',
        'in_planner' => 'boolean',
        'in_archive' => 'boolean',
        'is_combination' => 'boolean',
        'can_edit_content_during_planning' => 'boolean',
        'created' => 'datetime',
        'modified' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'price' => 'decimal:2',
        'subrental_costs' => 'decimal:2',
        'list_price' => 'decimal:2',
    ];

    /**
     *
     */
    public function customName(): Attribute
    {
        return Attribute::make(
            get: function () {
                return "custom_" . $this->rm_id;
            },
        );
    }

}