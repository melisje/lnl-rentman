<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class GoodsReceiptLine extends Model
{
    protected $table = 'po_goods_receipt_lines';
    protected $fillable = ['goods_receipt_id', 'po_line_id', 'received_quantity'];

    public function poLine()
    {
        return $this->belongsTo(PurchaseOrderLine::class);
    }
}
