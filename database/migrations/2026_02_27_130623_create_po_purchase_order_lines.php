<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('po_purchase_order_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('po_purchase_orders')->onDelete('cascade');
            $table->foreignId('internal_item_id')->constrained('po_internal_items');
            $table->foreignId('supplier_item_id')->nullable()->constrained('po_supplier_items');
            $table->decimal('qty_ordered', 15, 2);
            $table->decimal('unit_price', 15, 4);
            $table->decimal('vat_percentage', 5, 2)->default(21.00); // Belgische standaard
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_purchase_order_lines');
    }
};
