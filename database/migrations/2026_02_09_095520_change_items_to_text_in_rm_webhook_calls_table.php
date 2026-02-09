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
        Schema::table('rm_webhook_calls', function (Blueprint $table) {
            // Change colomn from string (varchar255) to text
            // to be able to a bigger payload
            $table->text('items')
                ->nullable()
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_webhook_calls', function (Blueprint $table) {
            // Let op: als de data in 'text' langer is dan 255 tekens,
            // kan dit dataverlies veroorzaken bij het terugdraaien naar string!
            $table->string('items', 255)
                ->nullable()
                ->change()
            ;
        });
    }
};
