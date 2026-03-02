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
        Schema::create('po_internal_items', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('description');
            $table->string('base_unit')->default('pcs'); // Standaard eenheid
            $table->boolean('is_active')->default(true);
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_internal_items');
    }
};
