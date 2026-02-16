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
        Schema::create('rm_accounts', function (Blueprint $table) {
            $table->string('account')->primary(); // Set account name as Primary Key
            $table->text('api_token')->nullable();
            $table->text('webhook_token')->nullable();
            $table->string('url')->nullable()->default('https://api.rentman.io/');
            $table->dbTimestamps(); // Handles created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_accounts');
    }
};
