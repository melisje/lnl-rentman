<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupplierInvoice extends Model
{
    protected $table = 'po_supplier_invoices';
    protected $fillable = ['supplier_id', 'purchase_order_id', 'invoice_number', 'invoice_date', 'total_amount', 'status'];

    // Oplossing voor Fout 2: Zorg dat invoice_date een Carbon object wordt
    protected $casts = [
        'invoice_date' => 'date',
    ];

    /**
     * De relatie naar de leverancier
     */
    public function supplier(): BelongsTo
    {
        // Omdat Supplier in dezelfde namespace zit, kunnen we Supplier::class gebruiken.
        // We definiëren expliciet de foreign key 'supplier_id' omdat we met po_ prefixes werken.
        return $this->belongsTo(Supplier::class, 'po_supplier_id');
    }

    /**
     * De relatie naar de bestelling
     */
    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_purchase_order_id');
    }

    /**
     * De relatie naar de factuurlijnen
     */
    public function lines(): HasMany
    {
        return $this->hasMany(SupplierInvoiceLine::class, 'supplier_invoice_id');
    }
}
