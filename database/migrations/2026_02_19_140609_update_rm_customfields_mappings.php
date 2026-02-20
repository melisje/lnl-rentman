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
        Schema::table('rm_customfield_mappings', function (Blueprint $table) {
            // $table->dropIndex('rm_customfield_mappings_mapping_id_foreign');
            $table->dropForeign(['mapping_id']);
            $table->renameColumn('mapping_id', 'customfield_id');
            $table->foreign('customfield_id')->references('id')->on('rm_customfields')->onDelete('cascade')->onUpdate('cascade');
             // $table->index('customfield_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_customfield_mappings', function (Blueprint $table)
        {
            $table->dropForeign(['customfield_id']);
            $table->renameColumn('customfield_id', 'mapping_id');
            $table->foreign('mapping_id')->references('id')->on('rm_mappings')->onDelete('cascade')->onUpdate('cascade');
        });
    }
};
