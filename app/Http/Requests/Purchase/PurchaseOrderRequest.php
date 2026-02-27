<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'po_number' => 'required|string|unique:purchase_orders,po_number',
            'order_date' => 'required|date',
            'lines' => 'required|array|min:1',
            'lines.*.internal_item_id' => 'required|exists:internal_items,id',
            'lines.*.qty_ordered' => 'required|numeric|min:0.01',
            'lines.*.unit_price' => 'required|numeric|min:0',
            'lines.*.vat_percentage' => 'required|in:0,6,12,21', // Belgische BTW-tarieven
        ];
    }
}
