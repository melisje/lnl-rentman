<?php

namespace App\Services\Purchase;

use App\Models\Purchase\SupplierInvoice;
use App\Models\Purchase\PurchaseOrderLine;
use Illuminate\Support\Facades\DB;
use Exception;

class InvoiceService
{
  /**
   * Verwerkt een factuur en controleert op afwijkingen.
   */
  public function createInvoice(array $data): SupplierInvoice
  {
    return DB::transaction(function () use ($data) {
      $invoice = SupplierInvoice::create([
        'supplier_id'       => $data['supplier_id'],
        'purchase_order_id' => $data['purchase_order_id'],
        'invoice_number'    => $data['invoice_number'],
        'invoice_date'      => $data['invoice_date'],
        'total_amount'      => $data['total_amount'],
        'status'            => 'pending_approval',
      ]);

      foreach ($data['lines'] as $lineData) {
        $poLine = PurchaseOrderLine::with(['receiptLines', 'invoiceLines'])
          ->findOrFail($lineData['po_line_id']);

        // 1. Prijscontrole: Wijkt de factuurprijs af van de PO?
        if ($lineData['unit_price'] > $poLine->ordered_unit_price) {
          $invoice->update(['status' => 'price_mismatch']);
        }

        // 2. Kwantiteitscontrole: Factureren we meer dan ontvangen?
        $totalReceived = $poLine->receiptLines->sum('received_quantity');
        $alreadyInvoiced = $poLine->invoiceLines->sum('invoiced_quantity');

        if (($alreadyInvoiced + $lineData['invoiced_quantity']) > $totalReceived) {
          $invoice->update(['status' => 'quantity_mismatch']);
        }

        $invoice->lines()->create([
          'po_line_id'        => $lineData['po_line_id'],
          'invoiced_quantity' => $lineData['invoiced_quantity'],
          'unit_price'        => $lineData['unit_price'],
          'line_amount'       => $lineData['invoiced_quantity'] * $lineData['unit_price'],
        ]);
      }

      // Als alles matcht, zetten we de status op 'approved'
      if ($invoice->status === 'pending_approval') {
        $invoice->update(['status' => 'approved']);
      }

      return $invoice;
    });
  }
}