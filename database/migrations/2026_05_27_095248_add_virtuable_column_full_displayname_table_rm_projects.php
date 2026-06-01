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
        Schema::table('rm_projects', function (Blueprint $table)
        {
            $table->string('full_displayname')
                ->storedAs("CONCAT(number, ' - ', name)")
                ->after('displayname')
                ;
            // Of ->virtualAs() afhankelijk van je databasevoorkeur
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_projects', function (Blueprint $table)
        {
            $table->dropColumn('full_displayname');
        });
    }
};
