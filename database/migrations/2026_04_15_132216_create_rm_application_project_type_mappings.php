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
        Schema::create('rm_application_project_type_mappings', function (Blueprint $table) {
            // $table->id();
            $table->string('account'); // Account veld voor de Global Scope filtering
            $table->foreign('account')->references('account')->on('rm_accounts')->cascadeOnDelete();
            $table->foreignId('application_id')->constrained('rm_applications', 'id')->cascadeOnDelete();
            $table->foreignId('project_type_id')->constrained('rm_project_types', 'id')->cascadeOnDelete();
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_application_project_type_mappings');
    }
};
