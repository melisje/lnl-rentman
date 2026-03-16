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
            $table->datetime('usageperiod_start')->nullable()->change();
            $table->datetime('usageperiod_end')->nullable()->change();
            $table->datetime('planperiod_start')->nullable()->change();
            $table->datetime('planperiod_end')->nullable()->change();
            $table->datetime('equipment_period_from')->nullable()->change();
            $table->datetime('equipment_period_to')->nullable()->change();
        });

        Schema::table('rm_subprojects', function (Blueprint $table) {
            //
            $table->datetime('usageperiod_start')->nullable()->change();
            $table->datetime('usageperiod_end')->nullable()->change();
            $table->datetime('planperiod_start')->nullable()->change();
            $table->datetime('planperiod_end')->nullable()->change();
            $table->datetime('equipment_period_from')->nullable()->change();
            $table->datetime('equipment_period_to')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rm_projects', function (Blueprint $table) {
            $table->string('usageperiod_start')->nullable()->change();
            $table->string('usageperiod_end')->nullable()->change();
            $table->string('planperiod_start')->nullable()->change();
            $table->string('planperiod_end')->nullable()->change();
            $table->string('equipment_period_from')->nullable()->change();
            $table->string('equipment_period_to')->nullable()->change();
        });

        Schema::table('rm_subprojects', function (Blueprint $table) {
            $table->timestamp('usageperiod_start')->nullable()->change();
            $table->timestamp('usageperiod_end')->nullable()->change();
            $table->timestamp('planperiod_start')->nullable()->change();
            $table->timestamp('planperiod_end')->nullable()->change();
            $table->timestamp('equipment_period_from')->nullable()->change();
            $table->timestamp('equipment_period_to')->nullable()->change();
        });
    }
};
