<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class GoodsReceipt extends Model
{
    protected $table = 'po_goods_receipts';
    protected $fillable = ['purchase_order_id', 'receipt_date', 'delivery_note_number', 'status'];

    public function lines()
    {
        return $this->hasMany(GoodsReceiptLine::class);
    }
}

