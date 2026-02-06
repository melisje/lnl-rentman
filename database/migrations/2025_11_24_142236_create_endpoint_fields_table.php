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
        Schema::create('rm_endpoint_fields', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('endpoint_id',unsigned:true);
            $table->string('name');
            $table->timestamps();

            $table->unique(['endpoint_id','name']);

            $table->foreign('endpoint_id')
                ->references('id')
                ->on('rm_endpoints')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_endpoint_fields');
    }
};
