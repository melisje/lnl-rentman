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
            $table->text('headers')
                ->nullable()
                ->after('payload')
                ;
            $table->string('ip')
                ->nullable()
                ->after('headers')
            ;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_webhook_calls', function (Blueprint $table) {
            $table->dropColumn(['ip','headers']);
        });
    }
};
