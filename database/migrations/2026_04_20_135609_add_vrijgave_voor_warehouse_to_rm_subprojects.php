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
        Schema::table('rm_subprojects', function (Blueprint $table) {
            //
            $table->boolean('vrijgave_voor_warehouse')->nullable()->after('custom');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_subprojects', function (Blueprint $table) {
            //
            $table->dropColumn('vrijgave_voor_warehouse');
        });
    }
};
