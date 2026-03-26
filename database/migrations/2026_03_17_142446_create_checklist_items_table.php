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
        Schema::create('prod_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('checklist_id')->nullable()->constrained('prod_checklists')->onDelete('cascade');
            $table->integer('sequence')->nullable();
            $table->string('name')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->longText('remarks')->nullable();
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod_checklist_items');
    }
};
