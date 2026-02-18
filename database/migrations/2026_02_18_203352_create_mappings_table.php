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
        Schema::create('rm_mappings', function (Blueprint $table) {
            $table->string('id')->primary() ->comment('Unique identifier for the mapping, e.g., "project_manager"');
            $table->string('display_name') ->comment('Human-readable name for the mapping, e.g., "Project Manager"');
            $table->dbTimestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rm_mappings');
    }
};
