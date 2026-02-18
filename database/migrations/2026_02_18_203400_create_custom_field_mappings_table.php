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
        Schema::create('rm_customfield_mappings', function (Blueprint $table) {
            $table->id();
            $table->string('account');
            $table->integer('rm_id');
            $table->string('mapping_id');
            $table->dbTimestamps();

            $table->unique(['account', 'rm_id']);
            $table->unique(['account', 'rm_id', 'mapping_id']);

            $table->foreign('mapping_id')->references('id')->on('rm_mappings')->onDelete('cascade')->onUpdate('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_customfield_mappings');
    }
};
