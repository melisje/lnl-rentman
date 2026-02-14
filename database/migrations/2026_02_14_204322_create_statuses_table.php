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
        Schema::create('rm_statuses', function (Blueprint $table) {
            $table->id(); //PK
            $table->string('account');
            $table->string('rm_id');

            // Rentman fields
            $table->timestamp('created')->nullable();
            $table->timestamp('modified')->nullable();
            $table->string('creator')->nullable();
            $table->string('displayname')->nullable();
            $table->string('name')->nullable();

            // add timestamp fields
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_statuses');
    }
};
