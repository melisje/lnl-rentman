<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;


class SupplierInvoiceLine extends Model
{
    protected $table = 'po_supplier_invoice_lines';
    protected $fillable = ['supplier_invoice_id', 'po_line_id', 'invoiced_quantity', 'unit_price', 'line_amount'];


    public function poLine()
    {
        return $this->belongsTo(PurchaseOrderLine::class);
    }
}