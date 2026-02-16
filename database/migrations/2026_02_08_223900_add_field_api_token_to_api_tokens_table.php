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
        Schema::table('rm_api_tokens', function (Blueprint $table)
        {
            $table->renameColumn('token', 'api_token');
            $table->text('webhook_token')
                ->after('api_token')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_api_tokens', function (Blueprint $table) {
            //
            $table->dropColumn('webhook_token');
            $table->renameColumn('api_token','token');
        });
    }
};
