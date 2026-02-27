<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseOrderLine extends Model
{
    protected $table = 'po_purchase_order_lines';
    protected $fillable = [
        'purchase_order_id',
        'internal_item_id',
        'supplier_item_id',
        'ordered_quantity',
        'ordered_unit_price',
        'vat_percentage'
    ];

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }
    public function internalItem(): BelongsTo
    {
        return $this->belongsTo(InternalItem::class, 'po_internal_item_id');
    }

    // Voor de 3-way match
    public function receiptLines(): HasMany
    {
        return $this->hasMany(GoodsReceiptLine::class, 'po_line_id');
    }
    public function invoiceLines(): HasMany
    {
        return $this->hasMany(SupplierInvoiceLine::class, 'po_line_id');
    }
}
