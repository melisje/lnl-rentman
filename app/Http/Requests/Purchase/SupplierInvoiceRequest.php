<?php

namespace App\Http\Requests\Purchase;

use Illuminate\Foundation\Http\FormRequest;

class SupplierInvoiceRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'supplier_id' => 'required|exists:suppliers,id',
            'purchase_order_id' => 'required|exists:purchase_orders,id',
            'invoice_number' => 'required|string',
            'invoice_date' => 'required|date',
            'lines' => 'required|array',
            'lines.*.po_line_id' => 'required|exists:purchase_order_lines,id',
            'lines.*.invoiced_quantity' => 'required|numeric',
            'lines.*.unit_price' => 'required|numeric',
            'total_amount' => 'required|numeric', // Check tegen som van lijnen
        ];
    }
}
