<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrder extends Model
{
    protected $table = 'po_purchase_orders';
    protected $fillable = ['po_number', 'supplier_id', 'order_date', 'expected_delivery_date', 'status', 'currency'];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
    public function lines(): HasMany
    {
        return $this->hasMany(PurchaseOrderLine::class);
    }
    public function goodsReceipts(): HasMany
    {
        return $this->hasMany(GoodsReceipt::class);
    }

    // Berekent totaalbedrag incl. BTW via de lijnen
    public function getTotalAmountAttribute()
    {
        return $this->lines->sum(function ($line) {
            return ($line->qty_ordered * $line->unit_price) * (1 + ($line->vat_percentage / 100));
        });
    }
}
