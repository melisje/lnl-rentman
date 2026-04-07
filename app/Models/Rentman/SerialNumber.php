<?php

namespace App\Models\Rentman;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SerialNumber extends Model
{
    // Expliciet de tabelnaam definiëren
    protected $table = 'rm_serialnumbers';

    /**
     * Velden die mass-assignable zijn.
     */
    // protected $fillable = [
    //     'account',
    //     'rm_id',
    //     'created',
    //     'modified',
    //     'creator',
    //     'displayname',
    //     'equipment',
    //     'serial',
    //     'purchasedate',
    //     'depreciation_monthly',
    //     'book_value',
    //     'residual_value',
    //     'purchase_costs',
    //     'active',
    //     'remark',
    //     'ref',
    //     'asset_location',
    //     'image',
    //     'current_book_value',
    //     'next_inspection',
    //     'qrcodes',
    //     'tags',
    //     'last_subproject',
    //     'sealed',
    //     'custom',
    // ];

    /**
     * Velden die niet mass-assignable zijn (om veiligheidsredenen).
     */
    protected $guarded = []; // Of gebruik $fillable zoals hierboven
    /**
     * Typecasting voor automatische conversie.
     */
    protected $casts = [
        'created' => 'datetime',
        'modified' => 'datetime',
        'active' => 'boolean',
        'sealed' => 'boolean',
        'custom' => 'array', // Converteert JSON in DB direct naar PHP array
        'depreciation_monthly' => 'float',
        'book_value' => 'float',
        'residual_value' => 'float',
        'purchase_costs' => 'float',
        'current_book_value' => 'float',
    ];

    public function equipment():BelongsTo
    {
        return $this->belongsTo(Equipment::class, 'equipment_id', 'id');
    }

    /**
     * Helper om enkel het ID uit het 'equipment' pad te halen.
     * Rentman geeft vaak "/equipment/1234", dit haalt "1234" op.
     */
    public function getEquipmentIdAttribute()
    {
        return filter_var($this->equipment, FILTER_SANITIZE_NUMBER_INT);
    }
}