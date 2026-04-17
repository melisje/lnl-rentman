<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('rm_application_project_type_mappings'))
        {
            Schema::rename('rm_application_project_type_mappings', 'rm_project_type_application_mappings');

            Schema::table('rm_project_type_application_mappings', function (Blueprint $table) {
                // We voegen de unieke index toe
                $table->unique(['account', 'project_type_id', 'application_id'], 'unique_project_type_application');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('rm_project_type_application_mappings', function (Blueprint $table) {
            $table->dropForeign('rm_application_project_type_mappings_account_foreign');
            $table->dropForeign('rm_application_project_type_mappings_application_id_foreign');
            $table->dropForeign('rm_application_project_type_mappings_project_type_id_foreign');
            $table->dropUnique('unique_project_type_application');

        });

        Schema::rename('rm_project_type_application_mappings', 'rm_application_project_type_mappings');
    }
};
