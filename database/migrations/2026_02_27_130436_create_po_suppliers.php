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
        Schema::create('po_suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vat_number')->nullable(); // Belangrijk voor BE (bv. BE0123.456.789)
            $table->string('email');
            $table->text('address')->nullable();
            $table->boolean('is_active')->default(true);
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('po_suppliers');
    }
};
