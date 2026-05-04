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
            // Rename the table
            Schema::rename('rm_application_project_type_mappings', 'rm_project_type_application_mappings');

            // Add the new 'id' column and unique index
            Schema::table('rm_project_type_application_mappings', function (Blueprint $table) {
                $table->id()->first();  // voeg een primary key toe
                // Add unique index on the combination of account, project_type_id, and application_id
                $table->unique(['account', 'project_type_id', 'application_id'], 'unique_project_type_application');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {

        Schema::table('rm_project_type_application_mappings', function (Blueprint $table)
        {
            // remove foreign keys before we can drop the unique index
            $table->dropForeign('rm_application_project_type_mappings_account_foreign');
            $table->dropForeign('rm_application_project_type_mappings_application_id_foreign');
            $table->dropForeign('rm_application_project_type_mappings_project_type_id_foreign');

            // Drop the unique index
            $table->dropUnique('unique_project_type_application');

            // Remove the 'id' column if it exists
            $table->dropColumn('id');

        });

        // Rename the table back to its original name
        Schema::rename('rm_project_type_application_mappings', 'rm_application_project_type_mappings');

        // Recreate the foreign keys after renaming the table back
        Schema::table('rm_application_project_type_mappings', function (Blueprint $table)
        {
            $table->foreign('account')->references('account')->on('rm_accounts')->onDelete('cascade');
            $table->foreign('application_id')->references('id')->on('rm_applications')->onDelete('cascade');
            $table->foreign('project_type_id')->references('id')->on('rm_project_types')->onDelete('cascade');
        });
    }
};
