<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InternalItem extends Model
{
    protected $table = 'po_internal_items';
    protected $fillable = ['internal_reference', 'description', 'base_unit', 'is_active'];

    public function supplierItems(): HasMany
    {
        return $this->hasMany(SupplierItem::class, 'internal_item_id');
    }

    public function purchaseOrderLines(): HasMany
    {
        return $this->hasMany(PurchaseOrderLine::class, 'internal_item_id');
    }
}