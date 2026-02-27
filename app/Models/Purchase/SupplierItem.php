<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierItem extends Model
{
    protected $table = 'po_supplier_items';
    protected $fillable = [
        'supplier_id',
        'internal_item_id',
        'supplier_reference',
        'unit_price',
        'currency',
        'lead_time_days',
        'is_preferred'
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function internalItem(): BelongsTo
    {
        return $this->belongsTo(InternalItem::class);
    }
}