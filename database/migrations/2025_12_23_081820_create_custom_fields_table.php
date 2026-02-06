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
        Schema::create('rm_customfields', function (Blueprint $table) {
            $table->integer('id')->primary();
            $table->string('name')->unique();
            $table->string('belongs_to')->nullable();
            $table->string('type')->nullable();
            $table->string('hidden')->nullable();
            $table->string('private')->nullable();
            $table->boolean('mandatory')->nullable();

            // We want to set CURRENT_TIMESTAMP as default value for both time fields
            // and onupdate for the updated_at field
            $table->timestamp('created_at')
                ->useCurrent();
            $table->timestamp('updated_at')
                ->useCurrent()
                ->useCurrentOnUpdate();
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_customfields');
    }
};
