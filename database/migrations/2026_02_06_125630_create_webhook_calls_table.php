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
        Schema::create('rm_webhook_calls', function (Blueprint $table) {
            $table->id();
            $table->string("account")->nullable();
            $table->string("user")->nullable();
            $table->string("eventType")->nullable();
            $table->string("itemType")->nullable();
            $table->string("items")->nullable();
            $table->string("eventDate")->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_webhook_calls');
    }
};
