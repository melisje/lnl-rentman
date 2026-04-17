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
        Schema::table('rm_projects', function (Blueprint $table) {
            //
            if (!Schema::hasColumn('rm_projects', 'project_type_id'))
            {
                $table->foreignId('project_type_id')->after('project_type')->nullable()->constrained('rm_project_types', 'id')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_projects', function (Blueprint $table) {            //
            $table->dropForeign(['project_type_id']);
            $table->dropColumn('project_type_id');
        });
    }
};
