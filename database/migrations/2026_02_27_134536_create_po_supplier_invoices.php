<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('po_supplier_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('po_supplier_id')->constrained();
            $table->foreignId('po_purchase_order_id')->constrained();
            $table->string('invoice_number'); // Het officiële factuurnummer van de leverancier
            $table->date('invoice_date');
            $table->decimal('total_amount', 15, 2); // Totaalbedrag incl. of excl. afhankelijk van je boekhoudregel
            $table->string('status')->default('pending'); // pending, approved, price_mismatch, quantity_mismatch
            $table->timestamps();

            // Een leverancier mag nooit twee keer hetzelfde factuurnummer gebruiken
            $table->unique(['po_supplier_id', 'invoice_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('po_supplier_invoices');
    }
};
