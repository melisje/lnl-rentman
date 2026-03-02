<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('po_goods_receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained('po_purchase_orders');
            $table->date('receipt_date');
            $table->string('delivery_note_number')->nullable(); // Nummer op de papieren pakbon
            $table->string('status')->default('received'); // bijv. received, inspected, returned
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('po_goods_receipts');
    }
};