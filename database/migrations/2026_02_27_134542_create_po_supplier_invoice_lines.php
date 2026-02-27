<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('po_supplier_invoice_lines', function (Blueprint $table) {
            $table->id();
            // We gebruiken hier de standaard Laravel naamgeving voor de kolom,
            // ook al verwijst die naar een geprefixte tabel.
            $table->foreignId('supplier_invoice_id')->constrained('po_supplier_invoices');
            $table->foreignId('po_line_id')->constrained('po_purchase_order_lines');
            $table->decimal('invoiced_quantity', 15, 2);
            $table->decimal('unit_price', 15, 4);
            $table->decimal('line_amount', 15, 2);
            $table->dbTimestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('po_supplier_invoice_lines');
    }
};