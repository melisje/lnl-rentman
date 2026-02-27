<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class StoreGoodsReceiptRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'receipt_date' => 'required|date|before_or_equal:today',
            'delivery_note_number' => 'required|string',
            'lines' => 'required|array|min:1',
            'lines.*.po_line_id' => 'required|exists:purchase_order_lines,id',
            'lines.*.received_quantity' => 'required|numeric|min:0',
        ];
    }
}