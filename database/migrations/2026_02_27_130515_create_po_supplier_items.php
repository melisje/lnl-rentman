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
        Schema::create('po_supplier_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('supplier_id')->constrained('po_suppliers');
            $table->foreignId('internal_item_id')->constrained('po_internal_items');
            $table->string('reference');
            $table->decimal('unit_price', 15, 4);
            $table->string('currency', 3)->default('EUR');
            $table->integer('lead_time_days')->default(0);
            $table->boolean('is_preferred')->default(false);
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_supplier_items');
    }
};
