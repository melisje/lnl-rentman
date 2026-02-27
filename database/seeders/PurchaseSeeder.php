<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Purchase\Supplier;
use App\Models\Purchase\InternalItem;
use App\Models\Purchase\SupplierItem;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\PurchaseOrderLine;
use App\Models\Purchase\GoodsReceipt;
use App\Models\Purchase\GoodsReceiptLine;
use App\Models\Purchase\SupplierInvoice;
use Carbon\Carbon;

class PurchaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Maak een Leverancier (Belgische context)
        $supplier = Supplier::create([
            'name' => 'TechDistri België NV',
            'vat_number' => 'BE0876543210',
            'email' => 'sales@techdistri.be',
            'address' => 'Industriepark 12, 9000 Gent',
            'is_active' => true,
        ]);

        // 2. Maak Interne Items
        $laptop = InternalItem::create([
            'internal_reference' => 'IT-LAP-001',
            'description' => 'Dell Latitude 5540 Business Laptop',
            'base_unit' => 'pcs',
        ]);

        $monitor = InternalItem::create([
            'internal_reference' => 'IT-MON-024',
            'description' => 'Dell 24" Monitor UltraSharp',
            'base_unit' => 'pcs',
        ]);

        // 3. Koppel Items aan Leverancier
        SupplierItem::create([
            'po_supplier_id' => $supplier->id,
            'po_internal_item_id' => $laptop->id,
            'supplier_reference' => 'DELL-5540-X',
            'unit_price' => 1250.00,
            'currency' => 'EUR',
        ]);

        // 4. Maak een Purchase Order (Bestelling)
        $po = PurchaseOrder::create([
            'po_number' => 'PO-' . date('Y') . '-0001',
            'po_supplier_id' => $supplier->id,
            'order_date' => Carbon::now()->subDays(10),
            'status' => 'partial', // We simuleren een gedeeltelijke levering
            'currency' => 'EUR',
        ]);

        $poLine1 = PurchaseOrderLine::create([
            'po_purchase_order_id' => $po->id,
            'po_internal_item_id' => $laptop->id,
            'qty_ordered' => 5,
            'unit_price' => 1250.00,
            'vat_percentage' => 21.00,
        ]);

        $poLine2 = PurchaseOrderLine::create([
            'po_purchase_order_id' => $po->id,
            'po_internal_item_id' => $monitor->id,
            'qty_ordered' => 10,
            'unit_price' => 210.00,
            'vat_percentage' => 21.00,
        ]);

        // 5. Simuleer een Goederenontvangst (Goods Receipt)
        // We hebben slechts 3 laptops en 10 monitors ontvangen
        $gr = GoodsReceipt::create([
            'po_purchase_order_id' => $po->id,
            'receipt_date' => Carbon::now()->subDays(5),
            'delivery_note_number' => 'DN-998877',
            'status' => 'completed',
        ]);

        GoodsReceiptLine::create([
            'po_goods_receipt_id' => $gr->id,
            'po_line_id' => $poLine1->id,
            'received_quantity' => 3,
        ]);

        GoodsReceiptLine::create([
            'po_goods_receipt_id' => $gr->id,
            'po_line_id' => $poLine2->id,
            'received_quantity' => 10,
        ]);

        // 6. Simuleer een Factuur (Supplier Invoice)
        // Deze factuur klopt met de ontvangst
        $invoice = SupplierInvoice::create([
            'po_supplier_id' => $supplier->id,
            'po_purchase_order_id' => $po->id,
            'invoice_number' => 'INV-2024-555',
            'invoice_date' => Carbon::now()->subDays(2),
            'total_amount' => 5850.00, // (3 * 1250) + (10 * 210)
            'status' => 'approved',
        ]);

        $invoice->lines()->create([
            'po_line_id' => $poLine1->id,
            'invoiced_quantity' => 3,
            'unit_price' => 1250.00,
            'line_amount' => 3750.00,
        ]);

        $invoice->lines()->create([
            'po_line_id' => $poLine2->id,
            'invoiced_quantity' => 10,
            'unit_price' => 210.00,
            'line_amount' => 2100.00,
        ]);

    }
}