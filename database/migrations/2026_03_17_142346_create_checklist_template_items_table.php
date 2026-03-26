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
        Schema::create('prod_checklist_template_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('template_id')->nullable()->constrained('prod_checklist_templates')->onDelete('cascade');
            $table->integer('sequence')->nullable();
            $table->string('name')->nullable();
            $table->boolean('completed')->default(false);
            $table->longText('remarks')->nullable();
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prod_checklist_template_items');
    }
};
